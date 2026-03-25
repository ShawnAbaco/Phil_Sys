<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Header extends Component
{
    public $title;
    public $windowNum;
    public $userRole;
    public $userName;
    public $userDesignation;

    /**
     * Create a new component instance.
     */
    public function __construct($title = 'National ID System')
    {
        $this->title = $title;
        $this->windowNum = session('window_num');
        $this->userName = session('full_name');
        $this->userDesignation = session('designation');
        $this->userRole = session('user_role'); // Make sure this is set in your login
    }

    /**
     * Determine if the current user is an operator or assistant
     * Both have window numbers and use the same UI
     */
    public function isOperator()
    {
        $designation = strtolower($this->userDesignation ?? '');
        // Both operators and assistants are considered "operators" for UI purposes
        return str_contains($designation, 'operator') || 
               str_contains($designation, 'assistant') ||
               $this->userRole === 'operator';
    }

    /**
     * Determine if the current user is a screener
     */
    public function isScreener()
    {
        $designation = strtolower($this->userDesignation ?? '');
        return $this->userRole === 'screener' || 
               str_contains($designation, 'screener');
    }

    /**
     * Determine if window badge should be shown
     * Show for both operators and assistants since both have window numbers
     */
    public function showWindowBadge()
    {
        return $this->isOperator() && $this->windowNum;
    }

    /**
     * Get the portal badge text
     */
    public function getPortalBadge()
    {
        $designation = strtolower($this->userDesignation ?? '');
        
        if (str_contains($designation, 'operator')) {
            return 'OPERATOR PORTAL';
        } elseif (str_contains($designation, 'assistant')) {
            return 'ASSISTANT PORTAL';
        } elseif ($this->isScreener()) {
            return 'SCREENER DASHBOARD';
        }
        
        return 'PORTAL';
    }

    /**
     * Get user role greeting text
     */
    public function getUserRoleGreeting()
    {
        $designation = strtolower($this->userDesignation ?? '');
        
        if (str_contains($designation, 'operator')) {
            return 'Operator';
        } elseif (str_contains($designation, 'assistant')) {
            return 'Assistant';
        } elseif ($this->isScreener()) {
            return 'Screener';
        } elseif ($this->userRole === 'admin') {
            return 'Administrator';
        }
        
        return 'User';
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.header');
    }
}