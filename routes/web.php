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
Route::get("/project-profile", [RsnController::class, "project_profile"])->name("project_profile");
Route::get("/search-rooms", [RsnController::class, "search_rooms"])->name("search_rooms");
Route::get("/setup-demo/{token}", [RsnController::class, "setup_demo"])->name("setup_demo");

// Auth
Route::middleware("not_logged_in")->group(function () {
    Route::match(["get", "post"], "/login", [RsnController::class, "flogin"])->name("flogin");
    Route::match(["get", "post"], "/register", [RsnController::class, "fregister"])->name("fregister");
});
Route::get("/logout", [RsnController::class, "flogout"])->name("flogout");
Route::get("/dashboard", [RsnController::class, "dashboard"])->name("dashboard");
Route::post("/favorite", [RsnController::class, "toggle_favorite"])->middleware("restricted_access:customer,staff,owner")->name("toggle_favorite");

// Agency Owner Area
Route::prefix("owner")->middleware("restricted_access:owner")->group(function () {
    Route::get("/dashboard", [RsnController::class, "owner_dashboard"])->name("owner_dashboard");
    Route::get("/bookings", [RsnController::class, "owner_bookings"])->name("owner_bookings");
    Route::post("/bookings/{id}/approve", [RsnController::class, "owner_approve_booking"])->name("owner_approve_booking");
    Route::post("/bookings/{id}/reject", [RsnController::class, "owner_reject_booking"])->name("owner_reject_booking");
    Route::get("/catalog", [RsnController::class, "owner_catalog"])->name("owner_catalog");
    Route::post("/catalog/toggle", [RsnController::class, "owner_toggle_item"])->name("owner_toggle_item");
});

// Hotel Staff Area
Route::prefix("staff")->middleware("restricted_access:staff,owner")->group(function () {
    Route::get("/dashboard", [RsnController::class, "staff_dashboard"])->name("staff_dashboard");
    Route::get("/bookings", [RsnController::class, "staff_bookings"])->name("staff_bookings");
    Route::post("/bookings/{id}/check-in", [RsnController::class, "staff_check_in"])->name("staff_check_in");
    Route::post("/bookings/{id}/check-out", [RsnController::class, "staff_check_out"])->name("staff_check_out");
    Route::get("/rooms", [RsnController::class, "staff_rooms"])->name("staff_rooms");
    Route::post("/rooms/{id}/status", [RsnController::class, "staff_set_room"])->name("staff_set_room");
});

// Customer Area
Route::prefix("customer")->middleware("restricted_access:customer")->group(function () {
    Route::get("/dashboard", [RsnController::class, "customer_dashboard"])->name("customer_dashboard");
    Route::get("/cart", [RsnController::class, "customer_cart"])->name("customer_cart");
    Route::post("/cart/add", [RsnController::class, "customer_add_to_cart"])->name("customer_add_to_cart");
    Route::post("/cart/{key}/remove", [RsnController::class, "customer_remove_from_cart"])->name("customer_remove_from_cart");
    Route::post("/checkout", [RsnController::class, "customer_checkout"])->name("customer_checkout");
    Route::get("/bookings", [RsnController::class, "customer_bookings"])->name("customer_bookings");
    Route::post("/bookings/{id}/cancel", [RsnController::class, "customer_cancel_booking"])->name("customer_cancel_booking");
    Route::get("/bookings/{id}/invoice", [RsnController::class, "customer_invoice"])->name("customer_invoice");
    Route::post("/bookings/{id}/review", [RsnController::class, "customer_review"])->name("customer_review");
    Route::get("/favorites", [RsnController::class, "customer_favorites"])->name("customer_favorites");
});
