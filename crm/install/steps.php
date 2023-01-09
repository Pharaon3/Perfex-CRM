<nav aria-label="Progress">
    <ol role="list"
        class="tw-divide-y tw-divide-solid tw-divide-neutral-200 tw-rounded-md tw-border tw-border-solid tw-border-neutral-200 md:tw-flex md:tw-divide-y-0 tw-mb-4 tw-bg-white">
        <?php foreach ($steps as $stepIdx => $step) { ?>
        <li class="tw-relative md:tw-flex md:tw-flex-1">
            <?php if ($step['status'] === 'complete') { ?>
            <div class="tw-flex tw-w-full tw-items-center">
                <span class="tw-flex tw-items-center tw-px-5 tw-py-4 tw-text-sm tw-font-medium">
                    <span
                        class="tw-flex tw-h-7 tw-w-7 tw-flex-shrink-0 tw-items-center tw-justify-center tw-rounded-full <?= count($steps) === $current_step && $step['id'] === $current_step ? 'tw-bg-success-600' : 'tw-bg-primary-600'; ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="tw-h-5 w-5 tw-text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                    </span>
                    <span class="tw-ml-2.5 tw-text-sm tw-font-medium tw-text-neutral-900">
                        <?=$step['name']; ?>
                    </span>
                </span>
            </div>
            <?php } elseif ($step['status'] === 'current') { ?>
            <div class="tw-flex tw-items-center tw-px-5 tw-py-4 tw-text-sm tw-font-medium" aria-current="step">
                <span
                    class="tw-flex tw-h-7 tw-w-7 tw-flex-shrink-0 tw-items-center tw-justify-center tw-rounded-full tw-border-2 tw-border-solid tw-border-primary-600">
                    <span class="tw-text-primary-600">
                        <?=$step['id']; ?>
                    </span>
                </span>
                <span class="tw-ml-2.5 tw-text-sm tw-font-medium tw-text-primary-600">
                    <?=$step['name']; ?>
                </span>
            </div>
            <?php } else { ?>
            <div class="tw-flex tw-items-center">
                <span class="tw-flex tw-items-center tw-px-5 tw-py-4 tw-text-sm tw-font-medium">
                    <span
                        class="tw-flex tw-h-7 tw-w-7 tw-flex-shrink-0 tw-items-center tw-justify-center tw-rounded-full tw-border-2 tw-border-solid tw-border-neutral-300">
                        <span class="tw-text-neutral-500">
                            <?=$step['id']; ?>
                        </span>
                    </span>
                    <span class="tw-ml-2.5 tw-text-sm tw-font-medium tw-text-neutral-500">
                        <?=$step['name']; ?>
                    </span>
                </span>
            </div>
            <?php } ?>
            <?php if ($stepIdx !== count($steps) - 1) { ?>
            <!-- Arrow separator for lg screens and up -->
            <div class="tw-absolute tw-top-0 tw-right-0 tw-hidden tw-h-full tw-w-5 md:tw-block" aria-hidden="true">
                <svg class="tw-h-full tw-w-full tw-text-neutral-300" viewBox="0 0 22 80" fill="none"
                    preserveAspectRatio="none">
                    <path d="M0 -2L20 40L0 82" vector-effect="non-scaling-stroke" stroke="currentcolor"
                        stroke-linejoin="round" />
                </svg>
            </div>
            <?php } ?>
        </li>
        <?php } ?>
    </ol>
</nav>