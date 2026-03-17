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
     * Determine if the current user is an operator
     */
    public function isOperator()
    {
        return $this->userRole === 'operator' ||
               str_contains(strtolower($this->userDesignation ?? ''), 'operator');
    }

    /**
     * Determine if the current user is a screener
     */
    public function isScreener()
    {
        return $this->userRole === 'screener' ||
               str_contains(strtolower($this->userDesignation ?? ''), 'screener');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.header');
    }
}
