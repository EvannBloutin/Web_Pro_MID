<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Head Librarian',
            'email' => 'admin@library.test',
            'password' => 'password',
            'is_admin' => true,
        ]);

        User::create([
            'name' => 'Demo Member',
            'email' => 'member@library.test',
            'password' => 'password',
        ]);

        $categories = collect([
            'Computer Science' => 'Programming, algorithms and software engineering.',
            'Artificial Intelligence' => 'Machine learning, search and intelligent agents.',
            'Fiction' => 'Novels and short stories.',
            'History' => 'World and regional history.',
            'Science' => 'Physics, chemistry, biology and mathematics.',
        ])->map(fn ($desc, $name) => Category::create(['name' => $name, 'description' => $desc]));

        $books = [
            ['Computer Science', 'Clean Code', 'Robert C. Martin', '9780132350884', 2008, 3],
            ['Computer Science', 'The Pragmatic Programmer', 'Andrew Hunt, David Thomas', '9780135957059', 2019, 2],
            ['Computer Science', 'Introduction to Algorithms', 'Thomas H. Cormen et al.', '9780262046305', 2022, 2],
            ['Artificial Intelligence', 'Artificial Intelligence: A Modern Approach', 'Stuart Russell, Peter Norvig', '9780134610993', 2020, 4],
            ['Artificial Intelligence', 'Deep Learning', 'Ian Goodfellow et al.', '9780262035613', 2016, 2],
            ['Fiction', 'Laskar Pelangi', 'Andrea Hirata', '9789793062792', 2005, 3],
            ['Fiction', 'Bumi Manusia', 'Pramoedya Ananta Toer', '9789799731234', 1980, 2],
            ['Fiction', '1984', 'George Orwell', '9780451524935', 1949, 3],
            ['History', 'Sapiens', 'Yuval Noah Harari', '9780062316097', 2015, 2],
            ['History', 'Sejarah Indonesia Modern', 'M. C. Ricklefs', '9789793062310', 2008, 1],
            ['Science', 'A Brief History of Time', 'Stephen Hawking', '9780553380163', 1988, 2],
            ['Science', 'The Selfish Gene', 'Richard Dawkins', '9780198788607', 1976, 2],
        ];

        foreach ($books as [$cat, $title, $author, $isbn, $year, $copies]) {
            Book::create([
                'category_id' => $categories[$cat]->id,
                'title' => $title,
                'author' => $author,
                'isbn' => $isbn,
                'published_year' => $year,
                'copies_total' => $copies,
                'copies_available' => $copies,
                'description' => "{$title} by {$author}, published in {$year}.",
            ]);
        }
    }
}
