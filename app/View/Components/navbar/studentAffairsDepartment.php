<?php

namespace App\View\Components\navbar;

use App\Helpers\Constant;
use App\Models\ClassSessionRequest;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class studentAffairsDepartment extends Component
{
    /**
     * Create a new component instance.
     */
    public $countClassSession;
    public function __construct()
    {
        $this->countClassSession = ClassSessionRequest::where('status', Constant::CLASS_SESSION_STATUS['ACTIVE'])->count();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.navbar.student-affairs-department', [
            'countClassSession' => $this->countClassSession,
        ]);
    }
}
