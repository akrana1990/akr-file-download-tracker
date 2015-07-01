<?php

/* No direct access */
if (!defined('ABSPATH')) exit;
if (!defined('AFDT_BASE_FILE')) wp_die('What ?');


class Afdt_How_To_Use{

    public function how_to_display_form()
    {
        ?>
        <div id="poststuff">
            <div class="error">
                <p>Please use the below shortcode to display the download tracker form on the front-end.</p>
            </div>


            <div class="postbox">
                <h3>How to display the form with Shortcode</h3>
                <div class="inside">
                    <h1>
                        [akr_show_form file_ids="File-ID1,File-ID2,File-ID3"]
                    </h1>

                </div>
            </div>


            <div class="postbox">
                <h3>Instructions to follow before use shortcode</h3>
                <div class="inside">

                    <ul>
                        <li>1. Just copy and paste the above shortcode on the page.</li>
                        <li>2. Replace the File-ID1,File-ID2,File-ID3 with the origional file ids (For file ids see ID column in Media>Library section).</li>
                        <li>3. The titles of the attachments file will be show as dropdown for Grades on the frontend form.</li>
                        <li>4. You can put more file ids into the shortcode</li>
                    </ul>

                </div>
            </div>


        </div>
    <?php
    }

}