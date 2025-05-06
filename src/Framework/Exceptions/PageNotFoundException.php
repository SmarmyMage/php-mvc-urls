<?php

namespace App\Controllers;

use Framework\Exceptions\PageNotFoundException;

class PageController
{
    public function show($pageId)
    {
        try {
            $page = $this->findPageById($pageId);

            if (!$page) {
                throw new PageNotFoundException("The page with ID {$pageId} was not found.");
            }

            // Render the page
        } catch (PageNotFoundException $e) {
            // Handle the exception, for example, by showing a 404 error page
            echo $e->getMessage();
        }
    }

    private function findPageById($id)
    {
        // Logic to find the page by ID
        // Return null if not found
    }
}
