<?php


$tables = [
	"user" => [
		"id" => "INTEGER PRIMARY KEY",
		"username" => "TEXT NOT NULL",
		"password" => "TEXT NOT NULL",
	],
	"dns" => [
		"id" => "INTEGER PRIMARY KEY",
		"title" => "TEXT",
		"url" => "TEXT",
	],
	"note" => [
		"id" => "INTEGER PRIMARY KEY",
		"note_title" => "TEXT",
		"note_content" => "TEXT",
		"createdate" => "TEXT",
	],
	"welcome" => [
		"id" => "INTEGER PRIMARY KEY",
		"message_one" => "TEXT",
		"message_two" => "TEXT",
		"message_three" =>"TEXT",
	],
];

?>