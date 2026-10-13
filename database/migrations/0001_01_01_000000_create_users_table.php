<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         Schema::create('publishers' , function (Blueprint $table){
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();

        });

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('ranks', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->integer('borrow_number_limit');
            $table->timestamps();
        });


        Schema::create('libraries', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('number');
            $table->string('street');
            $table->string('city');
            $table->timestamps();
        });

        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('member_number')->unique();
            $table->string('name');
            $table->string('firstname');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->boolean('is_suspended')->default(false);

            $table->foreignId('role_id')->constrained('roles');
            $table->foreignId('library_id')->constrained('libraries');
            $table->foreignId('rank_id')->constrained('ranks');
            $table->timestamps();

        });

        Schema::create('kinds', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('authors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('firstname');
            $table->timestamps();
        });

        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('summary');
            $table->string('isbn')->unique();
            $table->integer('number_of_pages');
            $table->timestamps();
            $table->integer('year');
            $table->string('cover')->nullable();
            $table->foreignId('publisher_id')->constrained('publishers');
        });

        Schema::create('copy_books', function (Blueprint $table) {
            $table->id();
            $table->enum('state', ['neuf', 'bon_etat', 'abime', 'inexploitable', 'perdu'])->default('neuf');
            $table->string('barcode')->unique();
            $table->foreignId('book_id')->constrained('books')->cascadeOnDelete();
            $table->foreignId('library_id')->constrained('libraries');
            $table->timestamps();

        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->foreignId('book_id')->constrained('books')->cascadeOnDelete();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->primary(['book_id', 'member_id']);
            $table->unsignedTinyInteger('note');
            $table->text('comment');
            $table->timestamps();
        });

        Schema::create('reserves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained('books')->cascadeOnDelete();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->timestamp('date_of_reserve')->useCurrent();
            $table->timestamp('date_of_disponibility')->nullable();
            $table->enum('state', ['en_attente', 'disponible', 'recuperee' , 'annulee'])->default('en_attente');
            $table->timestamps();
        });

        Schema::create('borrows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('copy_book_id')->constrained('copy_books');
            $table->foreignId('member_id')->constrained('members');
            $table->timestamp('date')->useCurrent();
            $table->timestamp('expected_return_date');
            $table->timestamp('actual_return_date')->nullable();
            $table->timestamps();
        });

        Schema::create('book_kind' , function (Blueprint $table){
            $table->primary(['book_id', 'kind_id']);
            $table->foreignId('book_id')->constrained('books')->cascadeOnDelete();
            $table->foreignId('kind_id')->constrained('kinds')->cascadeOnDelete();
            $table->timestamps();

        });

        Schema::create('book_language' , function (Blueprint $table){
            $table->primary(['book_id', 'language_id']);
            $table->foreignId('book_id')->constrained('books')->cascadeOnDelete();
            $table->foreignId('language_id')->constrained('languages')->cascadeOnDelete();
            $table->timestamps();

        });

        Schema::create('author_book' , function (Blueprint $table){
            $table->primary(['book_id', 'author_id']);
            $table->foreignId('book_id')->constrained('books')->cascadeOnDelete();
            $table->foreignId('author_id')->constrained('authors')->cascadeOnDelete();
            $table->timestamps();

        });

    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('author_book');
        Schema::drop('book_language');
        Schema::drop('book_kind');
        Schema::drop('borrows');
        Schema::drop('reserves');
        Schema::drop('reviews');
        Schema::drop('copy_books');
        Schema::drop('books');
        Schema::drop('languages');
        Schema::drop('authors');
        Schema::drop('kinds');
        Schema::drop('members');
        Schema::drop('libraries');
        Schema::drop('ranks');
        Schema::drop('roles');
        Schema::drop('publishers');
    }
};
