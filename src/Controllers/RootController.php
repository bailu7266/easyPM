<?php

namespace easyPM\Controllers;

class RootController extends AbstractController {
    public function root(): string {
        $properties = [
            'login' => 0,
            'title' => 'welcome',
            'content' => '以下是空白',
        ];
        return $this->render('welcome.twig', $properties);
    }
}
