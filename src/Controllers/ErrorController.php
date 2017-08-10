<?php

namespace easyPM\Controllers;

class ErrorController extends AbstractController {
    public function notFound(): string {
        $properties = [
            'login' => 0,
            'title' => 'Missing Page',
            'errorMessage' => 'Page not found!',
        ];
        return $this->render('error.twig', $properties);
    }
}
