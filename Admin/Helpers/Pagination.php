<?php

/**
 * Pagination Helper
 * Centralizes pagination logic for list pages.
 * Handles offset calculation, page count, and link generation.
 */

class Pagination
{
    private int $currentPage;
    private int $itemsPerPage;
    private int $totalItems;
    private int $totalPages;
    private int $offset;

    /**
     * Initialize pagination with current page and totals.
     * 
     * @param int $totalItems Total number of items
     * @param int $itemsPerPage Items per page (default from AdminConstants)
     * @param int $currentPage Current page number (from GET param or default 1)
     */
    public function __construct(int $totalItems, int $itemsPerPage = 10, int $currentPage = 1)
    {
        $this->totalItems = $totalItems;
        $this->itemsPerPage = $itemsPerPage;
        $this->currentPage = max(1, $currentPage); // Ensure >= 1
        $this->totalPages = max(1, ceil($totalItems / $itemsPerPage));

        // Clamp current page to valid range
        if ($this->currentPage > $this->totalPages) {
            $this->currentPage = $this->totalPages;
        }

        $this->offset = ($this->currentPage - 1) * $itemsPerPage;
    }

    /**
     * Get the LIMIT clause for SQL queries.
     */
    public function getSqlLimit(): string
    {
        return "LIMIT {$this->itemsPerPage} OFFSET {$this->offset}";
    }

    /**
     * Get the offset for use in OFFSET clause.
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * Get items per page.
     */
    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }

    /**
     * Get current page number.
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * Get total number of pages.
     */
    public function getTotalPages(): int
    {
        return $this->totalPages;
    }

    /**
     * Get total number of items.
     */
    public function getTotalItems(): int
    {
        return $this->totalItems;
    }

    /**
     * Check if there is a previous page.
     */
    public function hasPreviousPage(): bool
    {
        return $this->currentPage > 1;
    }

    /**
     * Check if there is a next page.
     */
    public function hasNextPage(): bool
    {
        return $this->currentPage < $this->totalPages;
    }

    /**
     * Get the previous page number.
     */
    public function getPreviousPage(): int
    {
        return max(1, $this->currentPage - 1);
    }

    /**
     * Get the next page number.
     */
    public function getNextPage(): int
    {
        return min($this->totalPages, $this->currentPage + 1);
    }

    /**
     * Get an array of page numbers for rendering pagination links.
     * Optionally limits the range (e.g., show 5 pages around current).
     * 
     * @param int $maxPagesToShow Max pages to show (0 = all)
     * @return array [['page' => 1, 'active' => false], ...]
     */
    public function getPageArray(int $maxPagesToShow = 0): array
    {
        $pages = [];
        $start = 1;
        $end = $this->totalPages;

        if ($maxPagesToShow > 0 && $this->totalPages > $maxPagesToShow) {
            $halfShow = intdiv($maxPagesToShow, 2);
            $start = max(1, $this->currentPage - $halfShow);
            $end = min($this->totalPages, $start + $maxPagesToShow - 1);
            $start = max(1, $end - $maxPagesToShow + 1); // Adjust if near end
        }

        for ($i = $start; $i <= $end; $i++) {
            $pages[] = [
                'page' => $i,
                'active' => $i === $this->currentPage,
            ];
        }

        return $pages;
    }

    /**
     * Get HTML for a simple pagination component.
     * 
     * @param string $baseUrl Base URL for links (e.g., "ListContestant.php?page=")
     * @return string HTML pagination markup
     */
    public function renderHtml(string $baseUrl): string
    {
        $html = '<div class="pagination" aria-label="Pagination">';

        if ($this->hasPreviousPage()) {
            $html .= '<a href="' . $baseUrl . $this->getPreviousPage() . '">Previous</a>';
        } else {
            $html .= '<span class="disabled">Previous</span>';
        }

        $pages = $this->getPageArray(7); // Show up to 7 pages
        foreach ($pages as $pageInfo) {
            if ($pageInfo['active']) {
                $html .= '<span class="current">' . $pageInfo['page'] . '</span>';
            } else {
                $html .= '<a href="' . $baseUrl . $pageInfo['page'] . '">' . $pageInfo['page'] . '</a>';
            }
        }

        if ($this->hasNextPage()) {
            $html .= '<a href="' . $baseUrl . $this->getNextPage() . '">Next</a>';
        } else {
            $html .= '<span class="disabled">Next</span>';
        }

        $html .= '</div>';
        return $html;
    }
}
