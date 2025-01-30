<?php

class AppController {
    protected function render(?string $template = null) {
        if (!$template) {
            print 'Template not specified';
            return;
        }

        $templatePath = 'public/views/'.$template.'.html';
        $output = 'File not found';

        if (file_exists($templatePath)) {
            ob_start();
            include $templatePath;
            $output = ob_get_clean();
        }

        print $output;
    }
}
