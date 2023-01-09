<?php

namespace app\services;

class MergeTickets
{
    /**
     * @var int
     */
    protected $primaryTicketId;

    /**
     * @var array
     */
    protected $ids;

    /**
     * @var int|null
     */
    protected $status;

    /**
     * CI Instance
     */
    protected $ci;

    /**
     * Initiate new MergeTickets class
     *
     * @param int $primaryTicketId
     * @param array $ids
     */
    public function __construct($primaryTicketId, $ids)
    {
        $this->primaryTicketId = $primaryTicketId;
        $this->ids             = $ids;
        $this->ci              = &get_instance();
    }

    /**
     * Merge the tickets into the primary ticket
     *
     * @return bool
     */
    public function merge()
    {
        $replies = $this->convertToMergeReplies(
            $this->getTicketsToMerge()
        );

        $merged = 0;
        $this->ci->db->trans_begin();

        try {
            foreach ($replies as $reply) {
                if ($this->mergeInPrimaryTicket($reply)) {
                    if ($reply['merge_type'] === 'ticket') {
                        $this->markTicketAsMerged($reply);
                    }

                    $merged++;
                }
            }

            if ($this->status && $merged > 0) {
                $this->ci->db->set('status', $this->status)
                    ->where('ticketid', $this->primaryTicketId)
                    ->update('tickets');
            }

            $this->ci->db->trans_commit();
        } catch (Exception $e) {
            $this->ci->db->trans_rollback();
        }

        return $merged > 0;
    }

    /**
     * After merge, change the primary ticket status to the given status
     *
     * @param  int $status
     *
     * @return $this
     */
    public function markPrimaryTicketAs($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Merge the given reply into the primary ticket
     *
     * @param  array $reply
     *
     * @return bool
     */
    protected function mergeInPrimaryTicket($reply)
    {
        $result = $this->ci->db->insert('ticket_replies', [
            'ticketid'  => $this->primaryTicketId,
            'userid'    => $reply['userid'],
            'contactid' => $reply['contactid'],
            'name'      => $reply['name'],
            'email'     => $reply['email'],
            'date'      => $reply['date'],
            'message'   => $reply['message'],
            'admin'     => $reply['admin'],
        ]);

        $replyId = $this->ci->db->insert_id();

        if (count($reply['attachments']) > 0) {
            $this->moveAttachments($reply['attachments'], $replyId);
        }

        return $result;
    }

    /**
     * Get the tickets to be merged into the primary ticket
     *
     * @return array
     */
    protected function getTicketsToMerge()
    {
        $tickets = $this->ci->db->where_in('ticketid', $this->ids)
            ->order_by('ticketid', 'ASC')
            ->get('tickets')
            ->result_array();

        return array_map(function ($ticket) {
            return array_merge($ticket, [
                'merge_type'  => 'ticket',
                'attachments' => $this->getAttachments($ticket['ticketid']),
                'replies'     => $this->getReplies($ticket['ticketid']),
            ]);
        }, $this->removeAlreadyMergedTickets($tickets));
    }

    /**
     * Get attachments for the merge
     *
     * @param  int $id
     * @param  int|null $replyId
     *
     * @return array
     */
    protected function getAttachments($id, $replyId = null)
    {
        return $this->ci->tickets_model->get_ticket_attachments($id, $replyId);
    }

    /**
     * Remove the already merged tickets from the given tickets list
     *
     * @param  array $tickets
     *
     * @return array
     */
    protected function removeAlreadyMergedTickets($tickets)
    {
        return array_values(
            array_filter($tickets, function ($ticket) {
                return $ticket['merged_ticket_id'] === null;
            })
        );
    }

    /**
     * Mark the ticket as merged
     *
     * @param  array $ticket
     *
     * @return void
     */
    protected function markTicketAsMerged($ticket)
    {
        $subject = strpos($ticket['subject'], '[MERGED]') !== false ?
                    $ticket['subject'] :
                    $ticket['subject'] . ' [MERGED]';

        $this->ci->db->set('merged_ticket_id', $this->primaryTicketId)
                        ->set('subject', $subject)
                        ->set('status', 5)
                        ->where('ticketid', $ticket['ticketid'])
                        ->update('tickets');
    }

    /**
       * Get the replies for merging for the given ticket
       *
       * @param  int $id
       *
       * @return array
       */
    protected function getReplies($id)
    {
        $this->ci->db->where('ticketid', $id);
        $replies = $this->ci->db->get('ticket_replies')->result_array();

        return array_map(function ($reply) use ($id) {
            return array_merge($reply, [
                'merge_type'  => 'reply',
                'attachments' => $this->getAttachments($id, $reply['id']),
            ]);

            return $reply;
        }, $replies);
    }

    /**
     * Convert the given tickets with replies to replies for ready for merging
     *
     * @param  array $tickets
     *
     * @return array
     */
    protected function convertToMergeReplies($tickets)
    {
        $replies = [];

        foreach ($tickets as $ticket) {
            $ticketReplies = $ticket['replies'];
            unset($ticket['replies']);
            $replies = array_merge($replies, [$ticket], $ticketReplies);
        }

        return $replies;
    }

    /**
     * Move the given attachment from merged ticket/reply to the new reply
     *
     * @param  array $attachment
     * @param  int $replyId
     *
     * @return void
     */
    protected function moveAttachments($attachments, $replyId)
    {
        $ticketsUploadPath = get_upload_path_by_type('ticket');
        $primaryTicketPath = $ticketsUploadPath . $this->primaryTicketId . DIRECTORY_SEPARATOR;
        _maybe_create_upload_path($primaryTicketPath);

        foreach ($attachments as $attachment) {
            $filePath = $ticketsUploadPath . $attachment['ticketid'] . DIRECTORY_SEPARATOR . $attachment['file_name'];

            $newFilename = unique_filename($primaryTicketPath, $attachment['file_name']);
            $newPath     = $primaryTicketPath . $newFilename;

            if (xcopy($filePath, $newPath)) {
                $this->ci->db->insert('ticket_attachments', [
                    'ticketid'  => $this->primaryTicketId,
                    'replyid'   => $replyId,
                    'file_name' => $newFilename,
                    'filetype'  => $attachment['filetype'],
                    'dateadded' => $attachment['dateadded'],
                ]);

                $this->ci->tickets_model->delete_ticket_attachment($attachment['id']);
            }
        }
    }
}
