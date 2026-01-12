<?php

namespace Database\Seeders;

use App\Enums\BlockType;
use App\Enums\CourseDifficulty;
use App\Models\BlockAssignment;
use App\Models\BlockAssignmentTest;
use App\Models\BlockQuiz;
use App\Models\BlockQuizQuestion;
use App\Models\BlockResource;
use App\Models\BlockText;
use App\Models\BlockVideo;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\CourseFaq;
use App\Models\Lesson;
use App\Models\LessonBlock;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            [
                'name' => 'Introduction to PHP',
                'thumbnail' => 'https://picsum.photos/seed/php/640/480',
                'description' => "Learn the fundamentals of PHP programming from scratch. This beginner-friendly course covers everything you need to know to start building dynamic websites and web applications.\n\nYou'll learn about variables, data types, control structures, functions, and more. By the end of this course, you'll have a solid foundation in PHP that will prepare you for more advanced topics like Laravel and WordPress development.",
                'difficulty' => CourseDifficulty::Easy,
                'faqs' => [
                    ['question' => 'Do I need any prior programming experience?', 'answer' => 'No! This course is designed for complete beginners. We start from the very basics and build up your knowledge step by step.'],
                    ['question' => 'What software do I need?', 'answer' => 'You\'ll need a text editor (VS Code is recommended) and PHP installed on your computer. We cover the installation process in the first chapter.'],
                    ['question' => 'How long does it take to complete?', 'answer' => 'Most students complete this course in 2-3 weeks, spending about 1-2 hours per day.'],
                ],
                'chapters' => [
                    [
                        'name' => 'Getting Started',
                        'lessons' => [
                            [
                                'name' => 'What is PHP?',
                                'blocks' => [
                                    ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=OK_JCtrrv-c', 'duration' => 480],
                                    ['type' => 'text', 'content' => "<h2>Welcome to PHP!</h2>\n<p>PHP (Hypertext Preprocessor) is a widely-used open source general-purpose scripting language that is especially suited for web development and can be embedded into HTML.</p>\n<h3>Why Learn PHP?</h3>\n<ul>\n<li><strong>Popularity:</strong> PHP powers over 75% of websites, including WordPress, Facebook, and Wikipedia</li>\n<li><strong>Easy to Learn:</strong> Simple syntax that's beginner-friendly</li>\n<li><strong>Great Community:</strong> Extensive documentation and active community support</li>\n<li><strong>Job Opportunities:</strong> High demand for PHP developers worldwide</li>\n</ul>"],
                                    ['type' => 'resources', 'links' => [
                                        ['title' => 'Official PHP Documentation', 'url' => 'https://www.php.net/docs.php'],
                                        ['title' => 'PHP: The Right Way', 'url' => 'https://phptherightway.com/'],
                                    ]],
                                ],
                            ],
                            [
                                'name' => 'Installing PHP',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "<h2>Installing PHP on Your Computer</h2>\n<p>Before we can start coding, we need to install PHP on your development machine. The installation process varies depending on your operating system.</p>\n<h3>Installation Options</h3>\n<p>There are several ways to install PHP:</p>\n<ol>\n<li><strong>XAMPP</strong> - All-in-one package with Apache, MySQL, and PHP (recommended for beginners)</li>\n<li><strong>Homebrew (Mac)</strong> - Package manager installation</li>\n<li><strong>apt-get (Linux)</strong> - Native package manager</li>\n<li><strong>Docker</strong> - Containerized PHP environment</li>\n</ol>"],
                                    ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=3B-CnezwEeo', 'duration' => 720],
                                    ['type' => 'resources', 'links' => [
                                        ['title' => 'Download XAMPP', 'url' => 'https://www.apachefriends.org/download.html'],
                                        ['title' => 'PHP Downloads', 'url' => 'https://www.php.net/downloads'],
                                    ]],
                                    ['type' => 'quiz', 'questions' => [
                                        ['question' => 'Which of these is an all-in-one package that includes PHP, Apache, and MySQL?', 'options' => ['XAMPP', 'Node.js', 'Python', 'Ruby'], 'correct' => 0],
                                        ['question' => 'What command would you use to check your PHP version?', 'options' => ['php -v', 'php --version', 'Both of the above', 'php info'], 'correct' => 2],
                                    ]],
                                ],
                            ],
                            [
                                'name' => 'Your First PHP Script',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "<h2>Writing Your First PHP Script</h2>\n<p>Let's write your very first PHP program! By tradition, we'll start with the famous \"Hello World\" example.</p>\n<h3>PHP Tags</h3>\n<p>PHP code is enclosed within special tags: <code>&lt;?php</code> and <code>?&gt;</code>. Everything between these tags is processed as PHP code.</p>"],
                                    ['type' => 'assignment', 'language' => 'php', 'instructions' => "Create a PHP script that outputs \"Hello, World!\" to the browser.\n\n**Requirements:**\n1. Use the `echo` statement to output text\n2. End your statement with a semicolon\n3. Make sure to include the opening PHP tag", 'starter_code' => "<?php\n// Write your code below\n\n", 'solution' => "<?php\necho \"Hello, World!\";\n", 'tests' => [['stdin' => '', 'expected_output' => 'Hello, World!']]],
                                ],
                            ],
                            [
                                'name' => 'PHP Syntax Basics',
                                'blocks' => [
                                    ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=pWG7ajC_OVo', 'duration' => 900],
                                    ['type' => 'text', 'content' => "<h2>PHP Syntax Fundamentals</h2>\n<p>Understanding PHP syntax is crucial for writing correct code. Let's explore the basic rules.</p>\n<h3>Key Syntax Rules</h3>\n<ul>\n<li><strong>Statements end with semicolons:</strong> Every PHP statement must end with <code>;</code></li>\n<li><strong>Case sensitivity:</strong> Variable names are case-sensitive, but function names are not</li>\n<li><strong>Comments:</strong> Use <code>//</code> for single-line or <code>/* */</code> for multi-line comments</li>\n<li><strong>Whitespace:</strong> PHP ignores whitespace between statements</li>\n</ul>"],
                                    ['type' => 'quiz', 'questions' => [
                                        ['question' => 'What character must end every PHP statement?', 'options' => ['Semicolon (;)', 'Colon (:)', 'Period (.)', 'Comma (,)'], 'correct' => 0],
                                        ['question' => 'Are PHP variable names case-sensitive?', 'options' => ['Yes', 'No', 'Only for constants', 'Only for functions'], 'correct' => 0],
                                    ]],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'Variables and Data Types',
                        'lessons' => [
                            [
                                'name' => 'Understanding Variables',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "<h2>Variables in PHP</h2>\n<p>Variables are containers for storing data values. In PHP, a variable starts with the <code>\$</code> sign, followed by the name of the variable.</p>\n<h3>Variable Naming Rules</h3>\n<ul>\n<li>Must start with a letter or underscore</li>\n<li>Cannot start with a number</li>\n<li>Can only contain letters, numbers, and underscores</li>\n<li>Are case-sensitive (<code>\$name</code> and <code>\$NAME</code> are different)</li>\n</ul>"],
                                    ['type' => 'assignment', 'language' => 'php', 'instructions' => "Practice creating variables by completing the following tasks:\n\n1. Create a variable called `\$name` and assign your name to it\n2. Create a variable called `\$age` and assign your age\n3. Create a variable called `\$isStudent` and assign a boolean value\n4. Echo all three variables", 'starter_code' => "<?php\n// Create your variables below\n\$name = \"\";\n\$age = 0;\n\$isStudent = true;\n\n// Output the variables\necho \"Name: \" . \$name;\n", 'solution' => "<?php\n// Create your variables below\n\$name = \"John\";\n\$age = 25;\n\$isStudent = true;\n\n// Output the variables\necho \"Name: \" . \$name . \"\\n\";\necho \"Age: \" . \$age . \"\\n\";\necho \"Is Student: \" . (\$isStudent ? \"Yes\" : \"No\");\n", 'tests' => [['stdin' => '', 'expected_output' => "Name: John\nAge: 25\nIs Student: Yes"]]],
                                ],
                            ],
                            [
                                'name' => 'Strings and Numbers',
                                'blocks' => [
                                    ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=nPJWdIOZ_cg', 'duration' => 660],
                                    ['type' => 'text', 'content' => "<h2>Working with Strings and Numbers</h2>\n<p>PHP supports several data types. The most common are strings and numbers.</p>\n<h3>Strings</h3>\n<p>Strings are sequences of characters enclosed in quotes. You can use single quotes <code>'</code> or double quotes <code>\"</code>.</p>\n<h3>Numbers</h3>\n<p>PHP supports integers (whole numbers) and floats (decimal numbers).</p>"],
                                    ['type' => 'resources', 'links' => [
                                        ['title' => 'PHP String Functions', 'url' => 'https://www.php.net/manual/en/ref.strings.php'],
                                        ['title' => 'PHP Math Functions', 'url' => 'https://www.php.net/manual/en/ref.math.php'],
                                    ]],
                                ],
                            ],
                            [
                                'name' => 'Arrays in PHP',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "<h2>Introduction to Arrays</h2>\n<p>Arrays are used to store multiple values in a single variable. PHP supports three types of arrays:</p>\n<ul>\n<li><strong>Indexed arrays:</strong> Arrays with numeric indexes</li>\n<li><strong>Associative arrays:</strong> Arrays with named keys</li>\n<li><strong>Multidimensional arrays:</strong> Arrays containing other arrays</li>\n</ul>"],
                                    ['type' => 'assignment', 'language' => 'php', 'instructions' => "Create different types of arrays:\n\n1. Create an indexed array of 5 fruits\n2. Create an associative array representing a person (name, age, city)\n3. Loop through each array and print the values", 'starter_code' => "<?php\n// Indexed array of fruits\n\$fruits = [\"apple\", \"banana\", \"orange\"];\n\n// Associative array\n\$person = [\n    \"name\" => \"\",\n    \"age\" => 0,\n    \"city\" => \"\"\n];\n\n// Loop through fruits\nforeach (\$fruits as \$fruit) {\n    echo \$fruit . \"\\n\";\n}\n", 'solution' => "<?php\n// Indexed array of fruits\n\$fruits = [\"apple\", \"banana\", \"orange\", \"grape\", \"mango\"];\n\n// Associative array\n\$person = [\n    \"name\" => \"John\",\n    \"age\" => 25,\n    \"city\" => \"New York\"\n];\n\n// Loop through fruits\necho \"Fruits:\\n\";\nforeach (\$fruits as \$fruit) {\n    echo \"- \" . \$fruit . \"\\n\";\n}\n\n// Loop through person\necho \"\\nPerson:\\n\";\nforeach (\$person as \$key => \$value) {\n    echo \$key . \": \" . \$value . \"\\n\";\n}\n", 'tests' => [['stdin' => '', 'expected_output' => "Fruits:\n- apple\n- banana\n- orange\n- grape\n- mango\n\nPerson:\nname: John\nage: 25\ncity: New York"]]],
                                    ['type' => 'quiz', 'questions' => [
                                        ['question' => 'How do you create an indexed array in PHP?', 'options' => ['$arr = [1, 2, 3]', '$arr = array(1, 2, 3)', 'Both of the above', '$arr = {1, 2, 3}'], 'correct' => 2],
                                    ]],
                                ],
                            ],
                            [
                                'name' => 'Working with Booleans',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "<h2>Booleans in PHP</h2>\n<p>A boolean represents one of two values: <code>true</code> or <code>false</code>. Booleans are commonly used in conditional testing.</p>\n<h3>Truthy and Falsy Values</h3>\n<p>PHP converts values to booleans in certain contexts. The following values are considered <code>false</code>:</p>\n<ul>\n<li>The boolean <code>false</code></li>\n<li>The integer <code>0</code></li>\n<li>The float <code>0.0</code></li>\n<li>An empty string <code>\"\"</code></li>\n<li>An empty array <code>[]</code></li>\n<li><code>null</code></li>\n</ul>"],
                                    ['type' => 'quiz', 'questions' => [
                                        ['question' => 'Which of these is NOT considered false in PHP?', 'options' => ['0', '""', '"0"', 'All are false'], 'correct' => 3],
                                        ['question' => 'What is the result of (bool) "hello"?', 'options' => ['true', 'false', 'error', '"hello"'], 'correct' => 0],
                                    ]],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'Control Structures',
                        'lessons' => [
                            [
                                'name' => 'If Statements',
                                'blocks' => [
                                    ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=LZzxWpBZrZg', 'duration' => 540],
                                    ['type' => 'text', 'content' => "<h2>Conditional Statements with If</h2>\n<p>The <code>if</code> statement executes code if a specified condition is true.</p>\n<h3>Syntax</h3>\n<pre><code>if (condition) {\n    // code to execute if condition is true\n} elseif (another_condition) {\n    // code for another condition\n} else {\n    // code if no conditions are true\n}</code></pre>"],
                                    ['type' => 'assignment', 'language' => 'php', 'instructions' => "Write a program that checks a student's grade:\n\n- 90-100: Output \"A - Excellent!\"\n- 80-89: Output \"B - Good job!\"\n- 70-79: Output \"C - Satisfactory\"\n- 60-69: Output \"D - Needs improvement\"\n- Below 60: Output \"F - Failed\"", 'starter_code' => "<?php\n\$grade = 85;\n\n// Write your if statements below\nif (\$grade >= 90) {\n    echo \"A - Excellent!\";\n}\n// Continue with elseif and else...\n", 'solution' => "<?php\n\$grade = 85;\n\nif (\$grade >= 90) {\n    echo \"A - Excellent!\";\n} elseif (\$grade >= 80) {\n    echo \"B - Good job!\";\n} elseif (\$grade >= 70) {\n    echo \"C - Satisfactory\";\n} elseif (\$grade >= 60) {\n    echo \"D - Needs improvement\";\n} else {\n    echo \"F - Failed\";\n}\n", 'tests' => [['stdin' => '', 'expected_output' => 'B - Good job!']]],
                                ],
                            ],
                            [
                                'name' => 'Switch Statements',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "<h2>Switch Statements</h2>\n<p>The <code>switch</code> statement is used to perform different actions based on different conditions. It's an alternative to multiple <code>if...elseif</code> statements.</p>\n<h3>When to Use Switch</h3>\n<p>Use switch when you have a single variable that you want to compare against multiple possible values.</p>"],
                                    ['type' => 'assignment', 'language' => 'php', 'instructions' => "Create a switch statement that outputs the day of the week based on a number (1-7):\n\n1 = Monday, 2 = Tuesday, etc.\n\nInclude a default case for invalid numbers.", 'starter_code' => "<?php\n\$dayNumber = 3;\n\nswitch (\$dayNumber) {\n    case 1:\n        echo \"Monday\";\n        break;\n    // Add more cases...\n}\n", 'solution' => "<?php\n\$dayNumber = 3;\n\nswitch (\$dayNumber) {\n    case 1:\n        echo \"Monday\";\n        break;\n    case 2:\n        echo \"Tuesday\";\n        break;\n    case 3:\n        echo \"Wednesday\";\n        break;\n    case 4:\n        echo \"Thursday\";\n        break;\n    case 5:\n        echo \"Friday\";\n        break;\n    case 6:\n        echo \"Saturday\";\n        break;\n    case 7:\n        echo \"Sunday\";\n        break;\n    default:\n        echo \"Invalid day number\";\n}\n", 'tests' => [['stdin' => '', 'expected_output' => 'Wednesday']]],
                                ],
                            ],
                            [
                                'name' => 'For and While Loops',
                                'blocks' => [
                                    ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=c_oLnP8vViM', 'duration' => 780],
                                    ['type' => 'text', 'content' => "<h2>Loops in PHP</h2>\n<p>Loops are used to execute the same block of code repeatedly.</p>\n<h3>For Loop</h3>\n<p>Use <code>for</code> when you know how many times you want to loop.</p>\n<h3>While Loop</h3>\n<p>Use <code>while</code> when you want to loop until a condition becomes false.</p>"],
                                    ['type' => 'assignment', 'language' => 'php', 'instructions' => "Complete both tasks:\n\n1. Use a for loop to print numbers 1 to 10\n2. Use a while loop to print the first 5 even numbers", 'starter_code' => "<?php\n// Task 1: For loop (1 to 10)\nfor (\$i = 1; \$i <= 10; \$i++) {\n    echo \$i . \" \";\n}\n\necho \"\\n\";\n\n// Task 2: While loop (first 5 even numbers)\n\$count = 0;\n\$num = 2;\nwhile (\$count < 5) {\n    // Your code here\n}\n", 'solution' => "<?php\n// Task 1: For loop (1 to 10)\nfor (\$i = 1; \$i <= 10; \$i++) {\n    echo \$i . \" \";\n}\n\necho \"\\n\";\n\n// Task 2: While loop (first 5 even numbers)\n\$count = 0;\n\$num = 2;\nwhile (\$count < 5) {\n    echo \$num . \" \";\n    \$num += 2;\n    \$count++;\n}\n", 'tests' => [['stdin' => '', 'expected_output' => "1 2 3 4 5 6 7 8 9 10 \n2 4 6 8 10 "]]],
                                ],
                            ],
                            [
                                'name' => 'Foreach Loops',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "<h2>Foreach Loops</h2>\n<p>The <code>foreach</code> loop is designed specifically for iterating over arrays. It's the most convenient way to loop through array elements.</p>\n<h3>Syntax</h3>\n<pre><code>// For indexed arrays\nforeach (\$array as \$value) {\n    // code\n}\n\n// For associative arrays\nforeach (\$array as \$key => \$value) {\n    // code\n}</code></pre>"],
                                    ['type' => 'quiz', 'questions' => [
                                        ['question' => 'Which loop is best for iterating over arrays?', 'options' => ['foreach', 'for', 'while', 'do-while'], 'correct' => 0],
                                        ['question' => 'In foreach($arr as $key => $value), what does $key represent?', 'options' => ['The array index or key', 'The array value', 'The array length', 'The array type'], 'correct' => 0],
                                    ]],
                                    ['type' => 'resources', 'links' => [
                                        ['title' => 'PHP Control Structures', 'url' => 'https://www.php.net/manual/en/language.control-structures.php'],
                                    ]],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Laravel Fundamentals',
                'thumbnail' => 'https://picsum.photos/seed/laravel/640/480',
                'description' => "Master the Laravel PHP framework and build modern web applications. This comprehensive course takes you from Laravel basics to building full-featured applications.\n\nYou'll learn about routing, controllers, Eloquent ORM, Blade templates, and much more. By the end, you'll be able to build and deploy professional Laravel applications.",
                'difficulty' => CourseDifficulty::Medium,
                'faqs' => [
                    ['question' => 'What prerequisites do I need?', 'answer' => 'You should have a solid understanding of PHP basics, including variables, arrays, functions, and object-oriented programming concepts.'],
                    ['question' => 'Which Laravel version is covered?', 'answer' => 'This course covers Laravel 11/12, the latest versions with all the modern features and best practices.'],
                    ['question' => 'Will I build real projects?', 'answer' => 'Yes! Throughout the course, you\'ll build several projects including a blog, API, and a full CRUD application.'],
                ],
                'chapters' => [
                    [
                        'name' => 'Laravel Basics',
                        'lessons' => [
                            [
                                'name' => 'Introduction to Laravel',
                                'blocks' => [
                                    ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY', 'duration' => 600],
                                    ['type' => 'text', 'content' => "<h2>What is Laravel?</h2>\n<p>Laravel is a free, open-source PHP web framework designed for building modern web applications following the MVC architectural pattern.</p>\n<h3>Why Laravel?</h3>\n<ul>\n<li><strong>Elegant Syntax:</strong> Clean, readable code that's enjoyable to write</li>\n<li><strong>Powerful ORM:</strong> Eloquent makes database operations intuitive</li>\n<li><strong>Built-in Features:</strong> Authentication, queues, caching, and more</li>\n<li><strong>Active Community:</strong> Extensive packages and documentation</li>\n</ul>"],
                                    ['type' => 'resources', 'links' => [
                                        ['title' => 'Laravel Documentation', 'url' => 'https://laravel.com/docs'],
                                        ['title' => 'Laracasts', 'url' => 'https://laracasts.com'],
                                    ]],
                                ],
                            ],
                            [
                                'name' => 'Installing Laravel',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "<h2>Installing Laravel</h2>\n<p>Laravel can be installed using Composer, PHP's dependency manager.</p>\n<h3>Prerequisites</h3>\n<ul>\n<li>PHP 8.2 or higher</li>\n<li>Composer</li>\n<li>Node.js and NPM (for frontend assets)</li>\n</ul>\n<h3>Installation Commands</h3>\n<pre><code># Create a new Laravel project\ncomposer create-project laravel/laravel my-app\n\n# Navigate to project directory\ncd my-app\n\n# Start the development server\nphp artisan serve</code></pre>"],
                                    ['type' => 'assignment', 'language' => 'php', 'instructions' => "After installing Laravel, verify your installation by:\n\n1. Running `php artisan serve`\n2. Visiting http://localhost:8000 in your browser\n3. Check the Laravel version with `php artisan --version`\n\nTake note of the Laravel version installed.", 'starter_code' => "# Terminal commands to run:\n\ncomposer create-project laravel/laravel my-first-app\ncd my-first-app\nphp artisan serve\n"],
                                ],
                            ],
                            [
                                'name' => 'Directory Structure',
                                'blocks' => [
                                    ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=cS_Xqj-DyQU', 'duration' => 720],
                                    ['type' => 'text', 'content' => "<h2>Laravel Directory Structure</h2>\n<p>Understanding Laravel's directory structure is crucial for working effectively with the framework.</p>\n<h3>Key Directories</h3>\n<ul>\n<li><strong>app/</strong> - Core application code (Controllers, Models, etc.)</li>\n<li><strong>config/</strong> - All configuration files</li>\n<li><strong>database/</strong> - Migrations, factories, and seeders</li>\n<li><strong>resources/</strong> - Views, CSS, JavaScript</li>\n<li><strong>routes/</strong> - All route definitions</li>\n<li><strong>storage/</strong> - Logs, cache, compiled files</li>\n</ul>"],
                                    ['type' => 'quiz', 'questions' => [
                                        ['question' => 'Where are Laravel controllers stored by default?', 'options' => ['app/Http/Controllers', 'controllers/', 'app/Controllers', 'Http/Controllers'], 'correct' => 0],
                                        ['question' => 'Which directory contains Blade view files?', 'options' => ['resources/views', 'views/', 'app/Views', 'templates/'], 'correct' => 0],
                                    ]],
                                ],
                            ],
                            [
                                'name' => 'Artisan CLI',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "<h2>Artisan Console</h2>\n<p>Artisan is Laravel's command-line interface. It provides helpful commands for development tasks.</p>\n<h3>Common Commands</h3>\n<pre><code># List all commands\nphp artisan list\n\n# Create a controller\nphp artisan make:controller UserController\n\n# Create a model with migration\nphp artisan make:model Post -m\n\n# Run migrations\nphp artisan migrate\n\n# Clear cache\nphp artisan cache:clear</code></pre>"],
                                    ['type' => 'resources', 'links' => [
                                        ['title' => 'Artisan Console Documentation', 'url' => 'https://laravel.com/docs/artisan'],
                                    ]],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'Routing and Controllers',
                        'lessons' => [
                            [
                                'name' => 'Basic Routing',
                                'blocks' => [
                                    ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=kz4WGMqGfZ0', 'duration' => 540],
                                    ['type' => 'text', 'content' => "<h2>Laravel Routing</h2>\n<p>Routes define the entry points to your application. All Laravel routes are defined in the <code>routes/</code> directory.</p>\n<h3>Basic Route Definition</h3>\n<pre><code>// routes/web.php\nuse Illuminate\\Support\\Facades\\Route;\n\n// Basic GET route\nRoute::get('/', function () {\n    return view('welcome');\n});\n\n// Route with parameter\nRoute::get('/user/{id}', function (string \$id) {\n    return 'User '.\$id;\n});</code></pre>"],
                                    ['type' => 'assignment', 'language' => 'php', 'instructions' => "Create the following routes in routes/web.php:\n\n1. A route for '/about' that returns an 'about' view\n2. A route for '/contact' that returns text 'Contact Us'\n3. A route for '/products/{id}' that returns the product ID", 'starter_code' => "<?php\n\nuse Illuminate\\Support\\Facades\\Route;\n\n// Home route\nRoute::get('/', function () {\n    return view('welcome');\n});\n\n// Add your routes below:\n\n"],
                                ],
                            ],
                            [
                                'name' => 'Route Parameters',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "<h2>Route Parameters</h2>\n<p>Route parameters allow you to capture segments of the URI within your route.</p>\n<h3>Required vs Optional Parameters</h3>\n<pre><code>// Required parameter\nRoute::get('/user/{id}', function (string \$id) {\n    return 'User '.\$id;\n});\n\n// Optional parameter\nRoute::get('/user/{name?}', function (?string \$name = 'Guest') {\n    return 'Hello '.\$name;\n});</code></pre>"],
                                    ['type' => 'quiz', 'questions' => [
                                        ['question' => 'How do you make a route parameter optional?', 'options' => ['Add ? after the parameter name', 'Use square brackets []', 'Add default= attribute', 'Use optional() function'], 'correct' => 0],
                                    ]],
                                ],
                            ],
                            [
                                'name' => 'Creating Controllers',
                                'blocks' => [
                                    ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=JdJYiHzL5ow', 'duration' => 660],
                                    ['type' => 'text', 'content' => "<h2>Controllers</h2>\n<p>Controllers group related request handling logic into a single class. They are stored in <code>app/Http/Controllers</code>.</p>\n<h3>Creating a Controller</h3>\n<pre><code># Create a basic controller\nphp artisan make:controller UserController\n\n# Create with resource methods\nphp artisan make:controller PostController --resource</code></pre>"],
                                    ['type' => 'assignment', 'language' => 'php', 'instructions' => 'Create a ProductController with an index and show method:\n\n1. Run: php artisan make:controller ProductController\n2. Add an index() method that returns a view\n3. Add a show($id) method that returns product details\n4. Register the routes in web.php', 'starter_code' => '<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // Return all products view
        return view(\'products.index\');
    }

    public function show(string $id)
    {
        // Return single product view
        return view(\'products.show\', [\'id\' => $id]);
    }
}
'],
                                ],
                            ],
                            [
                                'name' => 'Resource Controllers',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "<h2>Resource Controllers</h2>\n<p>Resource controllers handle all the typical CRUD operations for a resource with a single line of route registration.</p>\n<h3>Resource Route</h3>\n<pre><code>// Single line registers all CRUD routes\nRoute::resource('posts', PostController::class);</code></pre>\n<h3>Generated Routes</h3>\n<table>\n<tr><th>Verb</th><th>URI</th><th>Action</th></tr>\n<tr><td>GET</td><td>/posts</td><td>index</td></tr>\n<tr><td>GET</td><td>/posts/create</td><td>create</td></tr>\n<tr><td>POST</td><td>/posts</td><td>store</td></tr>\n<tr><td>GET</td><td>/posts/{id}</td><td>show</td></tr>\n<tr><td>GET</td><td>/posts/{id}/edit</td><td>edit</td></tr>\n<tr><td>PUT/PATCH</td><td>/posts/{id}</td><td>update</td></tr>\n<tr><td>DELETE</td><td>/posts/{id}</td><td>destroy</td></tr>\n</table>"],
                                    ['type' => 'quiz', 'questions' => [
                                        ['question' => 'How many routes does Route::resource() register?', 'options' => ['7', '5', '4', '10'], 'correct' => 0],
                                        ['question' => 'Which HTTP method is used for the update action?', 'options' => ['PUT or PATCH', 'POST', 'GET', 'UPDATE'], 'correct' => 0],
                                    ]],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'Eloquent ORM',
                        'lessons' => [
                            [
                                'name' => 'Introduction to Eloquent',
                                'blocks' => [
                                    ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=4Fo7Hm_H-ac', 'duration' => 720],
                                    ['type' => 'text', 'content' => "<h2>Eloquent ORM</h2>\n<p>Eloquent is Laravel's built-in ORM (Object-Relational Mapping) that makes working with databases enjoyable.</p>\n<h3>Key Features</h3>\n<ul>\n<li>Active Record implementation</li>\n<li>Expressive syntax for database operations</li>\n<li>Built-in relationship handling</li>\n<li>Automatic timestamps</li>\n<li>Soft deletes support</li>\n</ul>"],
                                ],
                            ],
                            [
                                'name' => 'Defining Models',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "<h2>Creating Eloquent Models</h2>\n<p>Models are classes that represent database tables. Each model instance represents a single row.</p>\n<h3>Creating a Model</h3>\n<pre><code># Create model only\nphp artisan make:model Post\n\n# Create with migration\nphp artisan make:model Post -m\n\n# Create with migration, factory, and seeder\nphp artisan make:model Post -mfs</code></pre>"],
                                    ['type' => 'assignment', 'language' => 'php', 'instructions' => "Create a Product model with:\n\n1. Migration for products table with: name, description, price, stock\n2. Define fillable properties in the model\n3. Add a scope for active products (stock > 0)", 'starter_code' => "<?php\n\nnamespace App\\Models;\n\nuse Illuminate\\Database\\Eloquent\\Model;\n\nclass Product extends Model\n{\n    protected \$fillable = [\n        'name',\n        'description',\n        'price',\n        'stock',\n    ];\n\n    // Add scope for active products\n    public function scopeActive(\$query)\n    {\n        return \$query->where('stock', '>', 0);\n    }\n}\n"],
                                ],
                            ],
                            [
                                'name' => 'CRUD Operations',
                                'blocks' => [
                                    ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=yJWPHPNt8nE', 'duration' => 900],
                                    ['type' => 'text', 'content' => "<h2>CRUD with Eloquent</h2>\n<p>Eloquent makes CRUD (Create, Read, Update, Delete) operations simple and intuitive.</p>\n<h3>Examples</h3>\n<pre><code>// Create\n\$post = Post::create(['title' => 'Hello']);\n\n// Read\n\$posts = Post::all();\n\$post = Post::find(1);\n\$post = Post::where('active', true)->first();\n\n// Update\n\$post->update(['title' => 'Updated']);\n\n// Delete\n\$post->delete();</code></pre>"],
                                    ['type' => 'quiz', 'questions' => [
                                        ['question' => 'Which method retrieves all records from a table?', 'options' => ['Model::all()', 'Model::get()', 'Model::fetch()', 'Model::select()'], 'correct' => 0],
                                        ['question' => 'What does Model::find(1) return if the record doesn\'t exist?', 'options' => ['null', 'false', 'An empty model', 'An exception'], 'correct' => 0],
                                    ]],
                                ],
                            ],
                            [
                                'name' => 'Relationships',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "<h2>Eloquent Relationships</h2>\n<p>Eloquent makes managing database relationships simple with intuitive methods.</p>\n<h3>Relationship Types</h3>\n<ul>\n<li><strong>hasOne / belongsTo:</strong> One-to-one relationship</li>\n<li><strong>hasMany / belongsTo:</strong> One-to-many relationship</li>\n<li><strong>belongsToMany:</strong> Many-to-many relationship</li>\n<li><strong>hasManyThrough:</strong> Has-many-through relationship</li>\n</ul>"],
                                    ['type' => 'assignment', 'language' => 'php', 'instructions' => "Define relationships for a blog:\n\n1. A User has many Posts\n2. A Post belongs to a User\n3. A Post has many Comments\n4. A Comment belongs to a Post and a User", 'starter_code' => "<?php\n// User.php\nclass User extends Model\n{\n    public function posts()\n    {\n        return \$this->hasMany(Post::class);\n    }\n}\n\n// Post.php\nclass Post extends Model\n{\n    public function user()\n    {\n        return \$this->belongsTo(User::class);\n    }\n\n    public function comments()\n    {\n        return \$this->hasMany(Comment::class);\n    }\n}\n"],
                                    ['type' => 'resources', 'links' => [
                                        ['title' => 'Eloquent Relationships', 'url' => 'https://laravel.com/docs/eloquent-relationships'],
                                    ]],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'Blade Templates',
                        'lessons' => [
                            [
                                'name' => 'Blade Syntax',
                                'blocks' => [
                                    ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=OC-sLyEB6Qg', 'duration' => 600],
                                    ['type' => 'text', 'content' => "<h2>Blade Templating Engine</h2>\n<p>Blade is Laravel's powerful templating engine that provides convenient shortcuts for common PHP control structures.</p>\n<h3>Basic Syntax</h3>\n<pre><code>{{-- This is a comment --}}\n\n{{-- Echo data (escaped) --}}\n{{ \$variable }}\n\n{{-- Echo raw data (unescaped) --}}\n{!! \$html !!}\n\n{{-- Control structures --}}\n@if (\$condition)\n    // content\n@elseif (\$other)\n    // content\n@else\n    // content\n@endif</code></pre>"],
                                ],
                            ],
                            [
                                'name' => 'Template Inheritance',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "<h2>Layouts and Sections</h2>\n<p>Blade allows you to define a master layout that child views can extend.</p>\n<h3>Master Layout</h3>\n<pre><code>{{-- layouts/app.blade.php --}}\n&lt;html&gt;\n&lt;head&gt;\n    &lt;title&gt;@yield('title')&lt;/title&gt;\n&lt;/head&gt;\n&lt;body&gt;\n    @yield('content')\n&lt;/body&gt;\n&lt;/html&gt;</code></pre>\n<h3>Child View</h3>\n<pre><code>{{-- pages/home.blade.php --}}\n@extends('layouts.app')\n\n@section('title', 'Home Page')\n\n@section('content')\n    &lt;h1&gt;Welcome!&lt;/h1&gt;\n@endsection</code></pre>"],
                                    ['type' => 'assignment', 'language' => 'html', 'instructions' => "Create a master layout with:\n\n1. A navigation section\n2. A content section\n3. A footer section\n\nThen create a home page that extends this layout.", 'starter_code' => "{{-- resources/views/layouts/app.blade.php --}}\n<!DOCTYPE html>\n<html>\n<head>\n    <title>@yield('title', 'My App')</title>\n</head>\n<body>\n    <nav>\n        @yield('navigation')\n    </nav>\n\n    <main>\n        @yield('content')\n    </main>\n\n    <footer>\n        @yield('footer', '&copy; 2024 My App')\n    </footer>\n</body>\n</html>\n"],
                                ],
                            ],
                            [
                                'name' => 'Components',
                                'blocks' => [
                                    ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=8oHfChSLCzM', 'duration' => 780],
                                    ['type' => 'text', 'content' => "<h2>Blade Components</h2>\n<p>Components provide a way to create reusable UI elements. Laravel supports both class-based and anonymous components.</p>\n<h3>Creating a Component</h3>\n<pre><code># Create a component\nphp artisan make:component Alert\n\n# This creates:\n# - app/View/Components/Alert.php\n# - resources/views/components/alert.blade.php</code></pre>"],
                                    ['type' => 'quiz', 'questions' => [
                                        ['question' => 'How do you use a component called Alert in Blade?', 'options' => ['<x-alert />', '@component(\'alert\')', '<Alert />', '{{ alert }}'], 'correct' => 0],
                                    ]],
                                ],
                            ],
                            [
                                'name' => 'Directives',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "<h2>Blade Directives</h2>\n<p>Blade provides many built-in directives for common tasks.</p>\n<h3>Common Directives</h3>\n<pre><code>@foreach (\$items as \$item)\n    {{ \$item }}\n@endforeach\n\n@forelse (\$items as \$item)\n    {{ \$item }}\n@empty\n    No items found.\n@endforelse\n\n@auth\n    User is logged in\n@endauth\n\n@guest\n    User is a guest\n@endguest</code></pre>"],
                                    ['type' => 'resources', 'links' => [
                                        ['title' => 'Blade Templates Documentation', 'url' => 'https://laravel.com/docs/blade'],
                                    ]],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Advanced React Patterns',
                'thumbnail' => 'https://picsum.photos/seed/react/640/480',
                'description' => "Take your React skills to the next level with advanced patterns and best practices. This course is designed for developers who already know React basics and want to write more maintainable, performant code.\n\nYou'll learn compound components, render props, custom hooks, state machines, and performance optimization techniques used by senior developers at top tech companies.",
                'difficulty' => CourseDifficulty::Hard,
                'faqs' => [
                    ['question' => 'Is this course for beginners?', 'answer' => 'No, this course requires solid React fundamentals including hooks, components, and basic state management. You should be comfortable building React applications.'],
                    ['question' => 'What tools will we use?', 'answer' => 'We\'ll use React 18+, TypeScript, and various libraries like XState for state machines and React Query for server state.'],
                    ['question' => 'Are there coding exercises?', 'answer' => 'Yes! Every lesson includes hands-on coding exercises where you\'ll implement the patterns yourself.'],
                ],
                'chapters' => [
                    [
                        'name' => 'Component Patterns',
                        'lessons' => [
                            [
                                'name' => 'Compound Components',
                                'blocks' => [
                                    ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=hEGg-3pIHlE', 'duration' => 900],
                                    ['type' => 'text', 'content' => "<h2>Compound Components Pattern</h2>\n<p>Compound components are a pattern where components work together to form a complete UI element while allowing flexible composition.</p>\n<h3>Example: Tabs Component</h3>\n<pre><code>&lt;Tabs&gt;\n  &lt;Tabs.List&gt;\n    &lt;Tabs.Tab&gt;Tab 1&lt;/Tabs.Tab&gt;\n    &lt;Tabs.Tab&gt;Tab 2&lt;/Tabs.Tab&gt;\n  &lt;/Tabs.List&gt;\n  &lt;Tabs.Panels&gt;\n    &lt;Tabs.Panel&gt;Content 1&lt;/Tabs.Panel&gt;\n    &lt;Tabs.Panel&gt;Content 2&lt;/Tabs.Panel&gt;\n  &lt;/Tabs.Panels&gt;\n&lt;/Tabs&gt;</code></pre>"],
                                    ['type' => 'assignment', 'language' => 'typescript', 'instructions' => "Create a compound Accordion component with:\n\n1. Accordion (parent)\n2. Accordion.Item\n3. Accordion.Header\n4. Accordion.Content\n\nUse React Context to share state between components.", 'starter_code' => "import { createContext, useContext, useState, ReactNode } from 'react';\n\nconst AccordionContext = createContext<{\n  activeIndex: number | null;\n  setActiveIndex: (index: number | null) => void;\n} | null>(null);\n\nexport function Accordion({ children }: { children: ReactNode }) {\n  const [activeIndex, setActiveIndex] = useState<number | null>(null);\n\n  return (\n    <AccordionContext.Provider value={{ activeIndex, setActiveIndex }}>\n      <div className=\"accordion\">{children}</div>\n    </AccordionContext.Provider>\n  );\n}\n\n// Add Accordion.Item, Accordion.Header, Accordion.Content\n"],
                                ],
                            ],
                            [
                                'name' => 'Render Props',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "<h2>Render Props Pattern</h2>\n<p>A render prop is a function prop that a component uses to know what to render. This pattern provides great flexibility for sharing code.</p>\n<h3>Basic Example</h3>\n<pre><code>function Mouse({ render }) {\n  const [position, setPosition] = useState({ x: 0, y: 0 });\n\n  // Track mouse position...\n\n  return render(position);\n}\n\n// Usage\n&lt;Mouse render={({ x, y }) =&gt; (\n  &lt;p&gt;Mouse at: {x}, {y}&lt;/p&gt;\n)} /&gt;</code></pre>"],
                                    ['type' => 'quiz', 'questions' => [
                                        ['question' => 'What is the main benefit of the render props pattern?', 'options' => ['Sharing stateful logic between components', 'Better performance', 'Smaller bundle size', 'Type safety'], 'correct' => 0],
                                    ]],
                                ],
                            ],
                            [
                                'name' => 'Higher-Order Components',
                                'blocks' => [
                                    ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=B6aNv8nkUSw', 'duration' => 720],
                                    ['type' => 'text', 'content' => "<h2>Higher-Order Components (HOCs)</h2>\n<p>A HOC is a function that takes a component and returns a new component with enhanced functionality.</p>\n<h3>Example: withAuth HOC</h3>\n<pre><code>function withAuth(WrappedComponent) {\n  return function AuthenticatedComponent(props) {\n    const { user } = useAuth();\n\n    if (!user) {\n      return &lt;Redirect to=\"/login\" /&gt;;\n    }\n\n    return &lt;WrappedComponent {...props} user={user} /&gt;;\n  };\n}\n\n// Usage\nconst ProtectedPage = withAuth(Dashboard);</code></pre>"],
                                    ['type' => 'assignment', 'language' => 'typescript', 'instructions' => "Create a withLoading HOC that:\n\n1. Shows a loading spinner while data is being fetched\n2. Passes the data to the wrapped component when ready\n3. Handles error states", 'starter_code' => "import { ComponentType, useState, useEffect } from 'react';\n\ninterface WithLoadingProps {\n  isLoading: boolean;\n  error: Error | null;\n}\n\nexport function withLoading<T extends object>(\n  WrappedComponent: ComponentType<T>,\n  fetchData: () => Promise<Partial<T>>\n) {\n  return function WithLoadingComponent(props: Omit<T, keyof WithLoadingProps>) {\n    const [isLoading, setIsLoading] = useState(true);\n    const [error, setError] = useState<Error | null>(null);\n    const [data, setData] = useState<Partial<T>>({});\n\n    useEffect(() => {\n      // Implement fetch logic here\n    }, []);\n\n    if (isLoading) return <div>Loading...</div>;\n    if (error) return <div>Error: {error.message}</div>;\n\n    return <WrappedComponent {...(props as T)} {...data} />;\n  };\n}\n"],
                                ],
                            ],
                            [
                                'name' => 'Custom Hooks',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "<h2>Custom Hooks</h2>\n<p>Custom hooks let you extract component logic into reusable functions. They're the modern alternative to HOCs and render props for sharing logic.</p>\n<h3>Example: useLocalStorage</h3>\n<pre><code>function useLocalStorage&lt;T&gt;(key: string, initialValue: T) {\n  const [storedValue, setStoredValue] = useState&lt;T&gt;(() =&gt; {\n    try {\n      const item = window.localStorage.getItem(key);\n      return item ? JSON.parse(item) : initialValue;\n    } catch {\n      return initialValue;\n    }\n  });\n\n  const setValue = (value: T) =&gt; {\n    setStoredValue(value);\n    window.localStorage.setItem(key, JSON.stringify(value));\n  };\n\n  return [storedValue, setValue] as const;\n}</code></pre>"],
                                    ['type' => 'assignment', 'language' => 'typescript', 'instructions' => "Create a useDebounce hook that:\n\n1. Takes a value and a delay\n2. Returns the debounced value\n3. Only updates after the specified delay", 'starter_code' => "import { useState, useEffect } from 'react';\n\nexport function useDebounce<T>(value: T, delay: number): T {\n  const [debouncedValue, setDebouncedValue] = useState<T>(value);\n\n  useEffect(() => {\n    // Set up the timeout\n    const handler = setTimeout(() => {\n      setDebouncedValue(value);\n    }, delay);\n\n    // Clean up on value change or unmount\n    return () => {\n      clearTimeout(handler);\n    };\n  }, [value, delay]);\n\n  return debouncedValue;\n}\n"],
                                    ['type' => 'resources', 'links' => [
                                        ['title' => 'Building Your Own Hooks', 'url' => 'https://react.dev/learn/reusing-logic-with-custom-hooks'],
                                        ['title' => 'useHooks Collection', 'url' => 'https://usehooks.com/'],
                                    ]],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'State Management',
                        'lessons' => [
                            [
                                'name' => 'Context API Deep Dive',
                                'blocks' => [
                                    ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=5LrDIWkK_Bc', 'duration' => 840],
                                    ['type' => 'text', 'content' => "<h2>React Context Deep Dive</h2>\n<p>Context provides a way to pass data through the component tree without prop drilling. But it needs to be used carefully to avoid performance issues.</p>\n<h3>Best Practices</h3>\n<ul>\n<li>Split contexts by concern (auth, theme, user preferences)</li>\n<li>Keep context values stable with useMemo</li>\n<li>Consider context selectors for performance</li>\n<li>Use multiple small contexts instead of one large one</li>\n</ul>"],
                                ],
                            ],
                            [
                                'name' => 'useReducer Patterns',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "<h2>useReducer for Complex State</h2>\n<p>useReducer is perfect for managing complex state logic. It's especially useful when state transitions depend on previous state.</p>\n<h3>Pattern: Action Creators</h3>\n<pre><code>const actions = {\n  increment: () =&gt; ({ type: 'INCREMENT' }),\n  decrement: () =&gt; ({ type: 'DECREMENT' }),\n  reset: (value: number) =&gt; ({ type: 'RESET', payload: value }),\n};</code></pre>"],
                                    ['type' => 'assignment', 'language' => 'typescript', 'instructions' => "Build a shopping cart reducer with:\n\n1. ADD_ITEM action\n2. REMOVE_ITEM action\n3. UPDATE_QUANTITY action\n4. CLEAR_CART action\n\nInclude proper TypeScript types.", 'starter_code' => "interface CartItem {\n  id: string;\n  name: string;\n  price: number;\n  quantity: number;\n}\n\ninterface CartState {\n  items: CartItem[];\n  total: number;\n}\n\ntype CartAction =\n  | { type: 'ADD_ITEM'; payload: Omit<CartItem, 'quantity'> }\n  | { type: 'REMOVE_ITEM'; payload: string }\n  | { type: 'UPDATE_QUANTITY'; payload: { id: string; quantity: number } }\n  | { type: 'CLEAR_CART' };\n\nfunction cartReducer(state: CartState, action: CartAction): CartState {\n  switch (action.type) {\n    case 'ADD_ITEM':\n      // Implement\n      return state;\n    // Add other cases\n    default:\n      return state;\n  }\n}\n"],
                                ],
                            ],
                            [
                                'name' => 'State Machines with XState',
                                'blocks' => [
                                    ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=iDZUKFDiMi4', 'duration' => 960],
                                    ['type' => 'text', 'content' => "<h2>State Machines</h2>\n<p>State machines provide a robust way to model complex UI states and transitions. XState is a popular library for this.</p>\n<h3>Benefits</h3>\n<ul>\n<li>Impossible states are truly impossible</li>\n<li>Clear visualization of state flow</li>\n<li>Predictable state transitions</li>\n<li>Easy to test and debug</li>\n</ul>"],
                                    ['type' => 'resources', 'links' => [
                                        ['title' => 'XState Documentation', 'url' => 'https://xstate.js.org/docs/'],
                                        ['title' => 'State Machine Visualizer', 'url' => 'https://stately.ai/viz'],
                                    ]],
                                ],
                            ],
                            [
                                'name' => 'Server State with React Query',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "<h2>React Query (TanStack Query)</h2>\n<p>React Query makes fetching, caching, and updating server state simple and powerful.</p>\n<h3>Key Concepts</h3>\n<ul>\n<li><strong>Queries:</strong> Fetch and cache data</li>\n<li><strong>Mutations:</strong> Create, update, delete data</li>\n<li><strong>Query Invalidation:</strong> Refetch stale data</li>\n<li><strong>Optimistic Updates:</strong> Update UI before server confirms</li>\n</ul>"],
                                    ['type' => 'quiz', 'questions' => [
                                        ['question' => 'What is the difference between server state and client state?', 'options' => ['Server state is stored remotely and needs synchronization', 'Server state is faster', 'There is no difference', 'Client state is more reliable'], 'correct' => 0],
                                    ]],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'Performance Optimization',
                        'lessons' => [
                            [
                                'name' => 'React.memo and useMemo',
                                'blocks' => [
                                    ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=uojLJFt9SzY', 'duration' => 720],
                                    ['type' => 'text', 'content' => "<h2>Memoization in React</h2>\n<p>Memoization prevents unnecessary re-renders and recalculations.</p>\n<h3>React.memo</h3>\n<p>Wraps a component to prevent re-renders if props haven't changed.</p>\n<h3>useMemo</h3>\n<p>Memoizes expensive calculations between renders.</p>\n<pre><code>const expensiveValue = useMemo(() =&gt; {\n  return computeExpensiveValue(a, b);\n}, [a, b]);</code></pre>"],
                                ],
                            ],
                            [
                                'name' => 'useCallback Best Practices',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "<h2>useCallback</h2>\n<p>useCallback memoizes functions to maintain referential equality between renders.</p>\n<h3>When to Use</h3>\n<ul>\n<li>Passing callbacks to optimized child components</li>\n<li>Dependencies for other hooks</li>\n<li>Event handlers in effects</li>\n</ul>\n<h3>When NOT to Use</h3>\n<ul>\n<li>Simple components that don't need optimization</li>\n<li>Callbacks that change every render anyway</li>\n</ul>"],
                                    ['type' => 'quiz', 'questions' => [
                                        ['question' => 'When should you use useCallback?', 'options' => ['When passing callbacks to memoized children', 'Always for all functions', 'Never - it\'s deprecated', 'Only for async functions'], 'correct' => 0],
                                    ]],
                                ],
                            ],
                            [
                                'name' => 'Code Splitting',
                                'blocks' => [
                                    ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=JU6sl_yyZqs', 'duration' => 660],
                                    ['type' => 'text', 'content' => "<h2>Code Splitting with React.lazy</h2>\n<p>Code splitting lets you split your code into smaller chunks that are loaded on demand.</p>\n<h3>Example</h3>\n<pre><code>import { lazy, Suspense } from 'react';\n\nconst HeavyComponent = lazy(() =&gt; import('./HeavyComponent'));\n\nfunction App() {\n  return (\n    &lt;Suspense fallback={&lt;Loading /&gt;}&gt;\n      &lt;HeavyComponent /&gt;\n    &lt;/Suspense&gt;\n  );\n}</code></pre>"],
                                    ['type' => 'assignment', 'language' => 'typescript', 'instructions' => "Implement route-based code splitting for:\n\n1. Dashboard page\n2. Settings page\n3. Profile page\n\nInclude proper loading states and error boundaries.", 'starter_code' => "import { lazy, Suspense } from 'react';\nimport { Routes, Route } from 'react-router-dom';\n\n// Lazy load the pages\nconst Dashboard = lazy(() => import('./pages/Dashboard'));\nconst Settings = lazy(() => import('./pages/Settings'));\nconst Profile = lazy(() => import('./pages/Profile'));\n\nfunction App() {\n  return (\n    <Suspense fallback={<div>Loading...</div>}>\n      <Routes>\n        <Route path=\"/\" element={<Dashboard />} />\n        {/* Add other routes */}\n      </Routes>\n    </Suspense>\n  );\n}\n"],
                                ],
                            ],
                            [
                                'name' => 'Virtual Lists',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "<h2>Virtualizing Long Lists</h2>\n<p>Virtual lists only render items that are visible in the viewport, dramatically improving performance for large datasets.</p>\n<h3>Popular Libraries</h3>\n<ul>\n<li><strong>react-window:</strong> Lightweight virtualization</li>\n<li><strong>react-virtuoso:</strong> Feature-rich with auto-sizing</li>\n<li><strong>TanStack Virtual:</strong> Framework-agnostic solution</li>\n</ul>"],
                                    ['type' => 'resources', 'links' => [
                                        ['title' => 'react-window Documentation', 'url' => 'https://react-window.vercel.app/'],
                                        ['title' => 'TanStack Virtual', 'url' => 'https://tanstack.com/virtual/latest'],
                                    ]],
                                    ['type' => 'quiz', 'questions' => [
                                        ['question' => 'Why use virtual lists?', 'options' => ['To render only visible items for better performance', 'To add animations', 'To sort items faster', 'To enable infinite scroll'], 'correct' => 0],
                                    ]],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($courses as $courseData) {
            $course = Course::create([
                'name' => $courseData['name'],
                'thumbnail' => $courseData['thumbnail'],
                'description' => $courseData['description'],
                'difficulty' => $courseData['difficulty'],
            ]);

            // Create FAQs
            if (isset($courseData['faqs'])) {
                foreach ($courseData['faqs'] as $index => $faqData) {
                    CourseFaq::create([
                        'course_id' => $course->id,
                        'question' => $faqData['question'],
                        'answer' => $faqData['answer'],
                        'order' => $index,
                    ]);
                }
            }

            foreach ($courseData['chapters'] as $chapterIndex => $chapterData) {
                $chapter = Chapter::create([
                    'name' => $chapterData['name'],
                    'course_id' => $course->id,
                    'position' => $chapterIndex + 1,
                ]);

                foreach ($chapterData['lessons'] as $lessonData) {
                    $lesson = Lesson::create([
                        'name' => $lessonData['name'],
                        'chapter_id' => $chapter->id,
                    ]);

                    // Create blocks for this lesson
                    if (isset($lessonData['blocks'])) {
                        foreach ($lessonData['blocks'] as $blockIndex => $blockData) {
                            $this->createBlock($lesson, $blockData, $blockIndex + 1);
                        }
                    }
                }
            }
        }
    }

    /**
     * Create a block for a lesson.
     *
     * @param  array<string, mixed>  $blockData
     */
    private function createBlock(Lesson $lesson, array $blockData, int $position): void
    {
        $type = match ($blockData['type']) {
            'video' => BlockType::Video,
            'text' => BlockType::Text,
            'resources' => BlockType::Resources,
            'assignment' => BlockType::Assignment,
            'quiz' => BlockType::Quiz,
            default => BlockType::Text,
        };

        $lessonBlock = LessonBlock::create([
            'lesson_id' => $lesson->id,
            'type' => $type,
            'position' => $position,
        ]);

        match ($blockData['type']) {
            'video' => BlockVideo::create([
                'block_id' => $lessonBlock->id,
                'url' => $blockData['url'],
                'duration' => $blockData['duration'] ?? null,
            ]),
            'text' => BlockText::create([
                'block_id' => $lessonBlock->id,
                'content' => $blockData['content'],
            ]),
            'resources' => BlockResource::create([
                'block_id' => $lessonBlock->id,
                'links' => $blockData['links'],
            ]),
            'assignment' => $this->createAssignment($lessonBlock, $blockData),
            'quiz' => $this->createQuiz($lessonBlock, $blockData['questions']),
            default => null,
        };
    }

    /**
     * Create an assignment with optional tests.
     *
     * @param  array<string, mixed>  $blockData
     */
    private function createAssignment(LessonBlock $lessonBlock, array $blockData): BlockAssignment
    {
        $assignment = BlockAssignment::create([
            'block_id' => $lessonBlock->id,
            'instructions' => $blockData['instructions'],
            'starter_code' => $blockData['starter_code'] ?? null,
            'solution' => $blockData['solution'] ?? null,
            'language' => $blockData['language'] ?? 'php',
        ]);

        if (isset($blockData['tests'])) {
            foreach ($blockData['tests'] as $testData) {
                BlockAssignmentTest::create([
                    'block_assignment_id' => $assignment->id,
                    'stdin' => $testData['stdin'] ?? null,
                    'expected_output' => $testData['expected_output'] ?? null,
                ]);
            }
        }

        return $assignment;
    }

    /**
     * Create a quiz with questions.
     *
     * @param  array<int, array{question: string, options: array<int, string>, correct: int}>  $questions
     */
    private function createQuiz(LessonBlock $lessonBlock, array $questions): void
    {
        $quiz = BlockQuiz::create([
            'block_id' => $lessonBlock->id,
        ]);

        foreach ($questions as $index => $questionData) {
            BlockQuizQuestion::create([
                'block_quiz_id' => $quiz->id,
                'question' => $questionData['question'],
                'options' => $questionData['options'],
                'correct_answer' => $questionData['correct'],
                'position' => $index + 1,
            ]);
        }
    }
}
