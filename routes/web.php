// routes/web.php
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// This is where you will eventually use the database
Route::get('/menu', function () {
    return "This is where the food list (Database) will go later!";
});