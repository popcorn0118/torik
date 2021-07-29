<?php defined('RUNCLOUD_HUB_INIT') || exit;?>

<ul data-tab-hash-default="<?php echo(self::stats_is_enabled() && self::is_main_site() && !self::is_subdirectory() && !self::is_client_mode() ? 'panel' : 'runcache');?>">
    <?php if (self::stats_is_enabled() && self::is_main_site() && !self::is_subdirectory() && !self::is_client_mode()): ?>
    <li>
        <a role="button" class="flex content-center block py-1 font-medium text-base-700 hover:text-base-1000" data-tab-hash="panel">
            <span class="rci-icon inline-block mr-3">
                <svg class="inline-block mr-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 32 28">
                    <path class="fill-current text-base-400 path-2" d="M32 24v2h-32v-24h2v22h30zM30 4.5v6.797c0 0.438-0.531 0.672-0.859 0.344l-1.891-1.891-9.891 9.891c-0.203 0.203-0.516 0.203-0.719 0l-3.641-3.641-6.5 6.5-3-3 9.141-9.141c0.203-0.203 0.516-0.203 0.719 0l3.641 3.641 7.25-7.25-1.891-1.891c-0.328-0.328-0.094-0.859 0.344-0.859h6.797c0.281 0 0.5 0.219 0.5 0.5z"></path>
                </svg>
            </span>
            <span class="inline-block pt-1"><?php esc_html_e('Stats', 'runcloud-hub');?></span>
        </a>
    </li>
    <?php endif;?>
    <li>
        <a role="button" class="flex content-center block py-1 font-medium text-base-700 hover:text-base-1000" data-tab-hash="runcache">
            <span class="rci-icon inline-block mr-3">
                <svg class="inline-block mr-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 30 28">
                    <path class="fill-current text-base-400 path-2" d="M14 22l5.25-6h-12l-5.25 6h12zM29.828 5.172c0.313 0.719 0.187 1.547-0.328 2.141l-14 16c-0.375 0.438-0.922 0.688-1.5 0.688h-12c-0.781 0-1.5-0.453-1.828-1.172-0.313-0.719-0.187-1.547 0.328-2.141l14-16c0.375-0.438 0.922-0.688 1.5-0.688h12c0.781 0 1.5 0.453 1.828 1.172z"></path>
                </svg>
            </span>
            <span class="inline-block pt-1"><?php esc_html_e('RunCache', 'runcloud-hub');?></span>
        </a>
        <a role="button" class="flex content-center block py-1 font-medium text-base-700 hover:text-base-1000 display-none" data-tab-hash="runcache-purger">
            <span class="inline-block ml-10"><?php esc_html_e('Purger', 'runcloud-hub');?></span>
        </a>
        <?php if (self::is_main_site() && !self::is_subdirectory()): ?>
        <a role="button" class="flex content-center block py-1 font-medium text-base-700 hover:text-base-1000 display-none" data-tab-hash="runcache-rules">
            <span class="inline-block ml-10"><?php esc_html_e('Rules', 'runcloud-hub');?></span>
        </a>
        <?php endif; ?>
        <a role="button" class="flex content-center block py-1 font-medium text-base-700 hover:text-base-1000 display-none" data-tab-hash="runcache-preload">
            <span class="inline-block ml-10"><?php esc_html_e('Preload', 'runcloud-hub');?></span>
        </a>
    </li>
    <li>
        <a role="button" class="flex content-center block py-1 font-medium text-base-700 hover:text-base-1000" data-tab-hash="redis">
            <span class="rci-icon inline-block mr-3">
                <svg class="inline-block mr-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 1000 1000">
                    <path class="fill-current text-base-400 path-2" d="M570.6,766c-57.8,30.1-89.7,29.8-135.4,8C389.8,752.2,10,593.5,10,593.5l0,0v115c0,9.9,13.8,20.3,39.6,32.7c51.9,24.9,340.2,141.1,385.5,163c45.4,21.8,77.5,21.9,135.4-8c57.8-30.1,328.4-141.2,380.7-168.7l0,0c26.6-13.8,38.3-24.7,38.3-34.4c0-9.2,0-113.6,0-113.6h-0.1C989.3,579.6,628.1,735.8,570.6,766z"></path>
                    <path class="fill-current text-base-400 path-2" d="M570.6,604.4c-57.8,30.1-89.7,29.8-135.4,8C389.8,590.5,10,431.7,10,431.7l0,0v115c0,9.9,13.8,20.3,39.6,32.7c51.9,25,340.2,141.2,385.5,163.2c45.4,21.8,77.5,21.9,135.4-8c57.8-30.1,328.4-141.2,380.7-168.7c26.6-13.8,38.3-24.7,38.3-34.4c0-9.2,0-113.6,0-113.6h-0.1C989.3,418,628.1,574.2,570.6,604.4z"></path>
                    <path class="fill-current text-base-400 path-2" d="M989.6,266.6c0.5-9.9-12.7-18.7-38.8-28.3c-51-18.7-320.1-125.8-371.7-144.7c-51.7-18.9-72.6-18-133.2,3.6C385.5,118.7,98.8,231.3,47.9,251.3c-25.7,10-38.2,19.5-37.5,29.5v-0.1v113.2c0,0,379.5,159.9,425.2,181.7c45.4,21.8,77.5,21.9,135.4-8C628.6,537.2,990,378.4,990,378.4L989.6,266.6L989.6,266.6L989.6,266.6z M862.8,269.6L714,328.3l-134.1-53l148.6-58.8L862.8,269.6z M588,325.4l-68.7,100.6L361,360.3L588,325.4z M468.8,172.5l-21.9-40.5l68.5,26.7l64.4-21.2l-17.5,41.8L628,204l-84.9,8.7L524,258.6l-30.6-51.1l-98-8.7L468.8,172.5z M299.7,229.5c67,0,121.4,20.9,121.4,47c0,25.8-54.4,47-121.4,47s-121.4-20.9-121.4-47C178.4,250.8,232.7,229.5,299.7,229.5z"></path>
                </svg>
            </span>
            <span class="inline-block pt-1"><?php esc_html_e('Redis Object Cache', 'runcloud-hub');?></span>
        </a>
    </li>
    <?php if (self::is_main_site() && !self::is_subdirectory() && !self::is_client_mode()): ?>
    <li>
        <a role="button" class="flex content-center block py-1 font-medium text-base-700 hover:text-base-1000" data-tab-hash="setting">
            <span class="rci-icon inline-block mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 28">
                    <path class="fill-current text-base-400 path-2" d="M16 14c0-2.203-1.797-4-4-4s-4 1.797-4 4 1.797 4 4 4 4-1.797 4-4zM24 12.297v3.469c0 0.234-0.187 0.516-0.438 0.562l-2.891 0.438c-0.172 0.5-0.359 0.969-0.609 1.422 0.531 0.766 1.094 1.453 1.672 2.156 0.094 0.109 0.156 0.25 0.156 0.391s-0.047 0.25-0.141 0.359c-0.375 0.5-2.484 2.797-3.016 2.797-0.141 0-0.281-0.063-0.406-0.141l-2.156-1.687c-0.453 0.234-0.938 0.438-1.422 0.594-0.109 0.953-0.203 1.969-0.453 2.906-0.063 0.25-0.281 0.438-0.562 0.438h-3.469c-0.281 0-0.531-0.203-0.562-0.469l-0.438-2.875c-0.484-0.156-0.953-0.344-1.406-0.578l-2.203 1.672c-0.109 0.094-0.25 0.141-0.391 0.141s-0.281-0.063-0.391-0.172c-0.828-0.75-1.922-1.719-2.578-2.625-0.078-0.109-0.109-0.234-0.109-0.359 0-0.141 0.047-0.25 0.125-0.359 0.531-0.719 1.109-1.406 1.641-2.141-0.266-0.5-0.484-1.016-0.641-1.547l-2.859-0.422c-0.266-0.047-0.453-0.297-0.453-0.562v-3.469c0-0.234 0.187-0.516 0.422-0.562l2.906-0.438c0.156-0.5 0.359-0.969 0.609-1.437-0.531-0.75-1.094-1.453-1.672-2.156-0.094-0.109-0.156-0.234-0.156-0.375s0.063-0.25 0.141-0.359c0.375-0.516 2.484-2.797 3.016-2.797 0.141 0 0.281 0.063 0.406 0.156l2.156 1.672c0.453-0.234 0.938-0.438 1.422-0.594 0.109-0.953 0.203-1.969 0.453-2.906 0.063-0.25 0.281-0.438 0.562-0.438h3.469c0.281 0 0.531 0.203 0.562 0.469l0.438 2.875c0.484 0.156 0.953 0.344 1.406 0.578l2.219-1.672c0.094-0.094 0.234-0.141 0.375-0.141s0.281 0.063 0.391 0.156c0.828 0.766 1.922 1.734 2.578 2.656 0.078 0.094 0.109 0.219 0.109 0.344 0 0.141-0.047 0.25-0.125 0.359-0.531 0.719-1.109 1.406-1.641 2.141 0.266 0.5 0.484 1.016 0.641 1.531l2.859 0.438c0.266 0.047 0.453 0.297 0.453 0.562z"></path>
                </svg>
            </span>
            <span class="inline-block pt-1"><?php esc_html_e('Settings', 'runcloud-hub');?></span>
        </a>
    </li>
    <?php endif; ?>
</ul>