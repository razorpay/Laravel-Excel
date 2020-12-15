<?php

namespace RZP\Maatwebsite\Excel\Tests\Data\Stubs;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use RZP\Maatwebsite\Excel\Concerns\Exportable;
use RZP\Maatwebsite\Excel\Concerns\FromView;

class SheetForUsersFromView implements FromView
{
    use Exportable;

    /**
     * @var Collection
     */
    protected $users;

    /**
     * @param Collection $users
     */
    public function __construct(Collection $users)
    {
        $this->users = $users;
    }

    /**
     * @return View
     */
    public function view(): View
    {
        return view('users', [
            'users' => $this->users,
        ]);
    }
}
