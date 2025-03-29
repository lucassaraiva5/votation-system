<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VoteController;


Route::get('/vote/{hash}', [VoteController::class, 'showVoteForm'])->name('vote.show');
Route::post('/vote/{hash}', [VoteController::class, 'submitVote'])->name('vote.submit');