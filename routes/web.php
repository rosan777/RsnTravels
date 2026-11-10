<?php

use App\Http\Controllers\RsnController;
use Illuminate\Support\Facades\Route;

// Public Area
Route::get("/", [RsnController::class, "index"])->name("index");
Route::get("/language/{code}", [RsnController::class, "set_language"])->name("set_language");
Route::get("/destinations", [RsnController::class, "view_destinations"])->name("view_destinations");
Route::get("/destinations/{id}", [RsnController::class, "view_destination"])->name("view_destination");
Route::get("/packages", [RsnController::class, "view_packages"])->name("view_packages");
Route::get("/packages/{id}", [RsnController::class, "view_package"])->name("view_package");
Route::get("/hotels", [RsnController::class, "view_hotels"])->name("view_hotels");
Route::get("/hotels/{id}", [RsnController::class, "view_hotel"])->name("view_hotel");
Route::get("/search-rooms", [RsnController::class, "search_rooms"])->name("search_rooms");

// Auth
Route::middleware("not_logged_in")->group(function () {
    Route::match(["get", "post"], "/login", [RsnController::class, "flogin"])->name("flogin");
    Route::match(["get", "post"], "/register", [RsnController::class, "fregister"])->name("fregister");
});
Route::get("/logout", [RsnController::class, "flogout"])->name("flogout");
Route::get("/dashboard", [RsnController::class, "dashboard"])->name("dashboard");

// Agency Owner Area
Route::prefix("owner")->middleware("restricted_access:owner")->group(function () {
    Route::get("/dashboard", [RsnController::class, "owner_dashboard"])->name("owner_dashboard");
});

// Hotel Staff Area
Route::prefix("staff")->middleware("restricted_access:staff,owner")->group(function () {
    Route::get("/dashboard", [RsnController::class, "staff_dashboard"])->name("staff_dashboard");
});

// Customer Area
Route::prefix("customer")->middleware("restricted_access:customer")->group(function () {
    Route::get("/dashboard", [RsnController::class, "customer_dashboard"])->name("customer_dashboard");
});
