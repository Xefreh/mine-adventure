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
                                    ['type' => 'text', 'content' => "## Welcome to PHP!\n\nPHP (Hypertext Preprocessor) is a widely-used open source general-purpose scripting language that is especially suited for web development and can be embedded into HTML.\n\n### Why Learn PHP?\n\n- **Popularity:** PHP powers over 75% of websites, including WordPress, Facebook, and Wikipedia\n- **Easy to Learn:** Simple syntax that's beginner-friendly\n- **Great Community:** Extensive documentation and active community support\n- **Job Opportunities:** High demand for PHP developers worldwide"],
                                    ['type' => 'resources', 'links' => [
                                        ['title' => 'Official PHP Documentation', 'url' => 'https://www.php.net/docs.php'],
                                        ['title' => 'PHP: The Right Way', 'url' => 'https://phptherightway.com/'],
                                    ]],
                                ],
                            ],
                            [
                                'name' => 'Installing PHP',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "## Installing PHP on Your Computer\n\nBefore we can start coding, we need to install PHP on your development machine. The installation process varies depending on your operating system.\n\n### Installation Options\n\nThere are several ways to install PHP:\n\n1. **XAMPP** - All-in-one package with Apache, MySQL, and PHP (recommended for beginners)\n2. **Homebrew (Mac)** - Package manager installation\n3. **apt-get (Linux)** - Native package manager\n4. **Docker** - Containerized PHP environment"],
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
                                    ['type' => 'text', 'content' => "## Writing Your First PHP Script\n\nLet's write your very first PHP program! By tradition, we'll start with the famous \"Hello World\" example.\n\n### PHP Tags\n\nPHP code is enclosed within special tags: `<?php` and `?>`. Everything between these tags is processed as PHP code."],
                                    ['type' => 'assignment', 'language' => 'php', 'instructions' => "Create a PHP script that outputs \"Hello, World!\" to the browser.\n\n**Requirements:**\n1. Use the `echo` statement to output text\n2. End your statement with a semicolon\n3. Make sure to include the opening PHP tag", 'starter_code' => "<?php\n// Write your code below\n\n", 'solution' => "<?php\necho \"Hello, World!\";\n", 'tests' => [['stdin' => '', 'expected_output' => 'Hello, World!']]],
                                ],
                            ],
                            [
                                'name' => 'PHP Syntax Basics',
                                'blocks' => [
                                    ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=pWG7ajC_OVo', 'duration' => 900],
                                    ['type' => 'text', 'content' => "## PHP Syntax Fundamentals\n\nUnderstanding PHP syntax is crucial for writing correct code. Let's explore the basic rules.\n\n### Key Syntax Rules\n\n- **Statements end with semicolons:** Every PHP statement must end with `;`\n- **Case sensitivity:** Variable names are case-sensitive, but function names are not\n- **Comments:** Use `//` for single-line or `/* */` for multi-line comments\n- **Whitespace:** PHP ignores whitespace between statements"],
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
                                    ['type' => 'text', 'content' => "## Variables in PHP\n\nVariables are containers for storing data values. In PHP, a variable starts with the `\$` sign, followed by the name of the variable.\n\n### Variable Naming Rules\n\n- Must start with a letter or underscore\n- Cannot start with a number\n- Can only contain letters, numbers, and underscores\n- Are case-sensitive (`\$name` and `\$NAME` are different)"],
                                    ['type' => 'assignment', 'language' => 'php', 'instructions' => "Practice creating variables by completing the following tasks:\n\n1. Create a variable called `\$name` and assign your name to it\n2. Create a variable called `\$age` and assign your age\n3. Create a variable called `\$isStudent` and assign a boolean value\n4. Echo all three variables", 'starter_code' => "<?php\n// Create your variables below\n\$name = \"\";\n\$age = 0;\n\$isStudent = true;\n\n// Output the variables\necho \"Name: \" . \$name;\n", 'solution' => "<?php\n// Create your variables below\n\$name = \"John\";\n\$age = 25;\n\$isStudent = true;\n\n// Output the variables\necho \"Name: \" . \$name . \"\\n\";\necho \"Age: \" . \$age . \"\\n\";\necho \"Is Student: \" . (\$isStudent ? \"Yes\" : \"No\");\n", 'tests' => [['stdin' => '', 'expected_output' => "Name: John\nAge: 25\nIs Student: Yes"]]],
                                ],
                            ],
                            [
                                'name' => 'Strings and Numbers',
                                'blocks' => [
                                    ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=nPJWdIOZ_cg', 'duration' => 660],
                                    ['type' => 'text', 'content' => "## Working with Strings and Numbers\n\nPHP supports several data types. The most common are strings and numbers.\n\n### Strings\n\nStrings are sequences of characters enclosed in quotes. You can use single quotes `'` or double quotes `\"`.\n\n### Numbers\n\nPHP supports integers (whole numbers) and floats (decimal numbers)."],
                                    ['type' => 'resources', 'links' => [
                                        ['title' => 'PHP String Functions', 'url' => 'https://www.php.net/manual/en/ref.strings.php'],
                                        ['title' => 'PHP Math Functions', 'url' => 'https://www.php.net/manual/en/ref.math.php'],
                                    ]],
                                ],
                            ],
                            [
                                'name' => 'Arrays in PHP',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "## Introduction to Arrays\n\nArrays are used to store multiple values in a single variable. PHP supports three types of arrays:\n\n- **Indexed arrays:** Arrays with numeric indexes\n- **Associative arrays:** Arrays with named keys\n- **Multidimensional arrays:** Arrays containing other arrays"],
                                    ['type' => 'assignment', 'language' => 'php', 'instructions' => "Create different types of arrays:\n\n1. Create an indexed array of 5 fruits\n2. Create an associative array representing a person (name, age, city)\n3. Loop through each array and print the values", 'starter_code' => "<?php\n// Indexed array of fruits\n\$fruits = [\"apple\", \"banana\", \"orange\"];\n\n// Associative array\n\$person = [\n    \"name\" => \"\",\n    \"age\" => 0,\n    \"city\" => \"\"\n];\n\n// Loop through fruits\nforeach (\$fruits as \$fruit) {\n    echo \$fruit . \"\\n\";\n}\n", 'solution' => "<?php\n// Indexed array of fruits\n\$fruits = [\"apple\", \"banana\", \"orange\", \"grape\", \"mango\"];\n\n// Associative array\n\$person = [\n    \"name\" => \"John\",\n    \"age\" => 25,\n    \"city\" => \"New York\"\n];\n\n// Loop through fruits\necho \"Fruits:\\n\";\nforeach (\$fruits as \$fruit) {\n    echo \"- \" . \$fruit . \"\\n\";\n}\n\n// Loop through person\necho \"\\nPerson:\\n\";\nforeach (\$person as \$key => \$value) {\n    echo \$key . \": \" . \$value . \"\\n\";\n}\n", 'tests' => [['stdin' => '', 'expected_output' => "Fruits:\n- apple\n- banana\n- orange\n- grape\n- mango\n\nPerson:\nname: John\nage: 25\ncity: New York"]]],
                                    ['type' => 'quiz', 'questions' => [
                                        ['question' => 'How do you create an indexed array in PHP?', 'options' => ['$arr = [1, 2, 3]', '$arr = array(1, 2, 3)', 'Both of the above', '$arr = {1, 2, 3}'], 'correct' => 2],
                                    ]],
                                ],
                            ],
                            [
                                'name' => 'Working with Booleans',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "## Booleans in PHP\n\nA boolean represents one of two values: `true` or `false`. Booleans are commonly used in conditional testing.\n\n### Truthy and Falsy Values\n\nPHP converts values to booleans in certain contexts. The following values are considered `false`:\n\n- The boolean `false`\n- The integer `0`\n- The float `0.0`\n- An empty string `\"\"`\n- An empty array `[]`\n- `null`"],
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
                                    ['type' => 'text', 'content' => "## Conditional Statements with If\n\nThe `if` statement executes code if a specified condition is true.\n\n### Syntax\n\n```php\nif (condition) {\n    // code to execute if condition is true\n} elseif (another_condition) {\n    // code for another condition\n} else {\n    // code if no conditions are true\n}\n```"],
                                    ['type' => 'assignment', 'language' => 'php', 'instructions' => "Write a program that checks a student's grade:\n\n- 90-100: Output \"A - Excellent!\"\n- 80-89: Output \"B - Good job!\"\n- 70-79: Output \"C - Satisfactory\"\n- 60-69: Output \"D - Needs improvement\"\n- Below 60: Output \"F - Failed\"", 'starter_code' => "<?php\n\$grade = 85;\n\n// Write your if statements below\nif (\$grade >= 90) {\n    echo \"A - Excellent!\";\n}\n// Continue with elseif and else...\n", 'solution' => "<?php\n\$grade = 85;\n\nif (\$grade >= 90) {\n    echo \"A - Excellent!\";\n} elseif (\$grade >= 80) {\n    echo \"B - Good job!\";\n} elseif (\$grade >= 70) {\n    echo \"C - Satisfactory\";\n} elseif (\$grade >= 60) {\n    echo \"D - Needs improvement\";\n} else {\n    echo \"F - Failed\";\n}\n", 'tests' => [['stdin' => '', 'expected_output' => 'B - Good job!']]],
                                ],
                            ],
                            [
                                'name' => 'Switch Statements',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "## Switch Statements\n\nThe `switch` statement is used to perform different actions based on different conditions. It's an alternative to multiple `if...elseif` statements.\n\n### When to Use Switch\n\nUse switch when you have a single variable that you want to compare against multiple possible values."],
                                    ['type' => 'assignment', 'language' => 'php', 'instructions' => "Create a switch statement that outputs the day of the week based on a number (1-7):\n\n1 = Monday, 2 = Tuesday, etc.\n\nInclude a default case for invalid numbers.", 'starter_code' => "<?php\n\$dayNumber = 3;\n\nswitch (\$dayNumber) {\n    case 1:\n        echo \"Monday\";\n        break;\n    // Add more cases...\n}\n", 'solution' => "<?php\n\$dayNumber = 3;\n\nswitch (\$dayNumber) {\n    case 1:\n        echo \"Monday\";\n        break;\n    case 2:\n        echo \"Tuesday\";\n        break;\n    case 3:\n        echo \"Wednesday\";\n        break;\n    case 4:\n        echo \"Thursday\";\n        break;\n    case 5:\n        echo \"Friday\";\n        break;\n    case 6:\n        echo \"Saturday\";\n        break;\n    case 7:\n        echo \"Sunday\";\n        break;\n    default:\n        echo \"Invalid day number\";\n}\n", 'tests' => [['stdin' => '', 'expected_output' => 'Wednesday']]],
                                ],
                            ],
                            [
                                'name' => 'For and While Loops',
                                'blocks' => [
                                    ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=c_oLnP8vViM', 'duration' => 780],
                                    ['type' => 'text', 'content' => "## Loops in PHP\n\nLoops are used to execute the same block of code repeatedly.\n\n### For Loop\n\nUse `for` when you know how many times you want to loop.\n\n### While Loop\n\nUse `while` when you want to loop until a condition becomes false."],
                                    ['type' => 'assignment', 'language' => 'php', 'instructions' => "Complete both tasks:\n\n1. Use a for loop to print numbers 1 to 10\n2. Use a while loop to print the first 5 even numbers", 'starter_code' => "<?php\n// Task 1: For loop (1 to 10)\nfor (\$i = 1; \$i <= 10; \$i++) {\n    echo \$i . \" \";\n}\n\necho \"\\n\";\n\n// Task 2: While loop (first 5 even numbers)\n\$count = 0;\n\$num = 2;\nwhile (\$count < 5) {\n    // Your code here\n}\n", 'solution' => "<?php\n// Task 1: For loop (1 to 10)\nfor (\$i = 1; \$i <= 10; \$i++) {\n    echo \$i . \" \";\n}\n\necho \"\\n\";\n\n// Task 2: While loop (first 5 even numbers)\n\$count = 0;\n\$num = 2;\nwhile (\$count < 5) {\n    echo \$num . \" \";\n    \$num += 2;\n    \$count++;\n}\n", 'tests' => [['stdin' => '', 'expected_output' => "1 2 3 4 5 6 7 8 9 10 \n2 4 6 8 10 "]]],
                                ],
                            ],
                            [
                                'name' => 'Foreach Loops',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "## Foreach Loops\n\nThe `foreach` loop is designed specifically for iterating over arrays. It's the most convenient way to loop through array elements.\n\n### Syntax\n\n```php\n// For indexed arrays\nforeach (\$array as \$value) {\n    // code\n}\n\n// For associative arrays\nforeach (\$array as \$key => \$value) {\n    // code\n}\n```"],
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
                                    ['type' => 'text', 'content' => "## What is Laravel?\n\nLaravel is a free, open-source PHP web framework designed for building modern web applications following the MVC architectural pattern.\n\n### Why Laravel?\n\n- **Elegant Syntax:** Clean, readable code that's enjoyable to write\n- **Powerful ORM:** Eloquent makes database operations intuitive\n- **Built-in Features:** Authentication, queues, caching, and more\n- **Active Community:** Extensive packages and documentation"],
                                    ['type' => 'resources', 'links' => [
                                        ['title' => 'Laravel Documentation', 'url' => 'https://laravel.com/docs'],
                                        ['title' => 'Laracasts', 'url' => 'https://laracasts.com'],
                                    ]],
                                ],
                            ],
                            [
                                'name' => 'Installing Laravel',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "## Installing Laravel\n\nLaravel can be installed using Composer, PHP's dependency manager.\n\n### Prerequisites\n\n- PHP 8.2 or higher\n- Composer\n- Node.js and NPM (for frontend assets)\n\n### Installation Commands\n\n```bash\n# Create a new Laravel project\ncomposer create-project laravel/laravel my-app\n\n# Navigate to project directory\ncd my-app\n\n# Start the development server\nphp artisan serve\n```"],
                                    ['type' => 'assignment', 'language' => 'php', 'instructions' => "After installing Laravel, verify your installation by:\n\n1. Running `php artisan serve`\n2. Visiting http://localhost:8000 in your browser\n3. Check the Laravel version with `php artisan --version`\n\nTake note of the Laravel version installed.", 'starter_code' => "# Terminal commands to run:\n\ncomposer create-project laravel/laravel my-first-app\ncd my-first-app\nphp artisan serve\n"],
                                ],
                            ],
                            [
                                'name' => 'Directory Structure',
                                'blocks' => [
                                    ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=cS_Xqj-DyQU', 'duration' => 720],
                                    ['type' => 'text', 'content' => "## Laravel Directory Structure\n\nUnderstanding Laravel's directory structure is crucial for working effectively with the framework.\n\n### Key Directories\n\n- **app/** - Core application code (Controllers, Models, etc.)\n- **config/** - All configuration files\n- **database/** - Migrations, factories, and seeders\n- **resources/** - Views, CSS, JavaScript\n- **routes/** - All route definitions\n- **storage/** - Logs, cache, compiled files"],
                                    ['type' => 'quiz', 'questions' => [
                                        ['question' => 'Where are Laravel controllers stored by default?', 'options' => ['app/Http/Controllers', 'controllers/', 'app/Controllers', 'Http/Controllers'], 'correct' => 0],
                                        ['question' => 'Which directory contains Blade view files?', 'options' => ['resources/views', 'views/', 'app/Views', 'templates/'], 'correct' => 0],
                                    ]],
                                ],
                            ],
                            [
                                'name' => 'Artisan CLI',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "## Artisan Console\n\nArtisan is Laravel's command-line interface. It provides helpful commands for development tasks.\n\n### Common Commands\n\n```bash\n# List all commands\nphp artisan list\n\n# Create a controller\nphp artisan make:controller UserController\n\n# Create a model with migration\nphp artisan make:model Post -m\n\n# Run migrations\nphp artisan migrate\n\n# Clear cache\nphp artisan cache:clear\n```"],
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
                                    ['type' => 'text', 'content' => "## Laravel Routing\n\nRoutes define the entry points to your application. All Laravel routes are defined in the `routes/` directory.\n\n### Basic Route Definition\n\n```php\n// routes/web.php\nuse Illuminate\\Support\\Facades\\Route;\n\n// Basic GET route\nRoute::get('/', function () {\n    return view('welcome');\n});\n\n// Route with parameter\nRoute::get('/user/{id}', function (string \$id) {\n    return 'User '.\$id;\n});\n```"],
                                    ['type' => 'assignment', 'language' => 'php', 'instructions' => "Create the following routes in routes/web.php:\n\n1. A route for '/about' that returns an 'about' view\n2. A route for '/contact' that returns text 'Contact Us'\n3. A route for '/products/{id}' that returns the product ID", 'starter_code' => "<?php\n\nuse Illuminate\\Support\\Facades\\Route;\n\n// Home route\nRoute::get('/', function () {\n    return view('welcome');\n});\n\n// Add your routes below:\n\n"],
                                ],
                            ],
                            [
                                'name' => 'Route Parameters',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "## Route Parameters\n\nRoute parameters allow you to capture segments of the URI within your route.\n\n### Required vs Optional Parameters\n\n```php\n// Required parameter\nRoute::get('/user/{id}', function (string \$id) {\n    return 'User '.\$id;\n});\n\n// Optional parameter\nRoute::get('/user/{name?}', function (?string \$name = 'Guest') {\n    return 'Hello '.\$name;\n});\n```"],
                                    ['type' => 'quiz', 'questions' => [
                                        ['question' => 'How do you make a route parameter optional?', 'options' => ['Add ? after the parameter name', 'Use square brackets []', 'Add default= attribute', 'Use optional() function'], 'correct' => 0],
                                    ]],
                                ],
                            ],
                            [
                                'name' => 'Creating Controllers',
                                'blocks' => [
                                    ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=JdJYiHzL5ow', 'duration' => 660],
                                    ['type' => 'text', 'content' => "## Controllers\n\nControllers group related request handling logic into a single class. They are stored in `app/Http/Controllers`.\n\n### Creating a Controller\n\n```bash\n# Create a basic controller\nphp artisan make:controller UserController\n\n# Create with resource methods\nphp artisan make:controller PostController --resource\n```"],
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
                                    ['type' => 'text', 'content' => "## Resource Controllers\n\nResource controllers handle all the typical CRUD operations for a resource with a single line of route registration.\n\n### Resource Route\n\n```php\n// Single line registers all CRUD routes\nRoute::resource('posts', PostController::class);\n```\n\n### Generated Routes\n\n| Verb | URI | Action |\n|------|-----|--------|\n| GET | /posts | index |\n| GET | /posts/create | create |\n| POST | /posts | store |\n| GET | /posts/{id} | show |\n| GET | /posts/{id}/edit | edit |\n| PUT/PATCH | /posts/{id} | update |\n| DELETE | /posts/{id} | destroy |"],
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
                                    ['type' => 'text', 'content' => "## Eloquent ORM\n\nEloquent is Laravel's built-in ORM (Object-Relational Mapping) that makes working with databases enjoyable.\n\n### Key Features\n\n- Active Record implementation\n- Expressive syntax for database operations\n- Built-in relationship handling\n- Automatic timestamps\n- Soft deletes support"],
                                ],
                            ],
                            [
                                'name' => 'Defining Models',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "## Creating Eloquent Models\n\nModels are classes that represent database tables. Each model instance represents a single row.\n\n### Creating a Model\n\n```bash\n# Create model only\nphp artisan make:model Post\n\n# Create with migration\nphp artisan make:model Post -m\n\n# Create with migration, factory, and seeder\nphp artisan make:model Post -mfs\n```"],
                                    ['type' => 'assignment', 'language' => 'php', 'instructions' => "Create a Product model with:\n\n1. Migration for products table with: name, description, price, stock\n2. Define fillable properties in the model\n3. Add a scope for active products (stock > 0)", 'starter_code' => "<?php\n\nnamespace App\\Models;\n\nuse Illuminate\\Database\\Eloquent\\Model;\n\nclass Product extends Model\n{\n    protected \$fillable = [\n        'name',\n        'description',\n        'price',\n        'stock',\n    ];\n\n    // Add scope for active products\n    public function scopeActive(\$query)\n    {\n        return \$query->where('stock', '>', 0);\n    }\n}\n"],
                                ],
                            ],
                            [
                                'name' => 'CRUD Operations',
                                'blocks' => [
                                    ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=yJWPHPNt8nE', 'duration' => 900],
                                    ['type' => 'text', 'content' => "## CRUD with Eloquent\n\nEloquent makes CRUD (Create, Read, Update, Delete) operations simple and intuitive.\n\n### Examples\n\n```php\n// Create\n\$post = Post::create(['title' => 'Hello']);\n\n// Read\n\$posts = Post::all();\n\$post = Post::find(1);\n\$post = Post::where('active', true)->first();\n\n// Update\n\$post->update(['title' => 'Updated']);\n\n// Delete\n\$post->delete();\n```"],
                                    ['type' => 'quiz', 'questions' => [
                                        ['question' => 'Which method retrieves all records from a table?', 'options' => ['Model::all()', 'Model::get()', 'Model::fetch()', 'Model::select()'], 'correct' => 0],
                                        ['question' => 'What does Model::find(1) return if the record doesn\'t exist?', 'options' => ['null', 'false', 'An empty model', 'An exception'], 'correct' => 0],
                                    ]],
                                ],
                            ],
                            [
                                'name' => 'Relationships',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "## Eloquent Relationships\n\nEloquent makes managing database relationships simple with intuitive methods.\n\n### Relationship Types\n\n- **hasOne / belongsTo:** One-to-one relationship\n- **hasMany / belongsTo:** One-to-many relationship\n- **belongsToMany:** Many-to-many relationship\n- **hasManyThrough:** Has-many-through relationship"],
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
                                    ['type' => 'text', 'content' => "## Blade Templating Engine\n\nBlade is Laravel's powerful templating engine that provides convenient shortcuts for common PHP control structures.\n\n### Basic Syntax\n\n```blade\n{{-- This is a comment --}}\n\n{{-- Echo data (escaped) --}}\n{{ \$variable }}\n\n{{-- Echo raw data (unescaped) --}}\n{!! \$html !!}\n\n{{-- Control structures --}}\n@if (\$condition)\n    // content\n@elseif (\$other)\n    // content\n@else\n    // content\n@endif\n```"],
                                ],
                            ],
                            [
                                'name' => 'Template Inheritance',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "## Layouts and Sections\n\nBlade allows you to define a master layout that child views can extend.\n\n### Master Layout\n\n```blade\n{{-- layouts/app.blade.php --}}\n<html>\n<head>\n    <title>@yield('title')</title>\n</head>\n<body>\n    @yield('content')\n</body>\n</html>\n```\n\n### Child View\n\n```blade\n{{-- pages/home.blade.php --}}\n@extends('layouts.app')\n\n@section('title', 'Home Page')\n\n@section('content')\n    <h1>Welcome!</h1>\n@endsection\n```"],
                                    ['type' => 'assignment', 'language' => 'html', 'instructions' => "Create a master layout with:\n\n1. A navigation section\n2. A content section\n3. A footer section\n\nThen create a home page that extends this layout.", 'starter_code' => "{{-- resources/views/layouts/app.blade.php --}}\n<!DOCTYPE html>\n<html>\n<head>\n    <title>@yield('title', 'My App')</title>\n</head>\n<body>\n    <nav>\n        @yield('navigation')\n    </nav>\n\n    <main>\n        @yield('content')\n    </main>\n\n    <footer>\n        @yield('footer', '&copy; 2024 My App')\n    </footer>\n</body>\n</html>\n"],
                                ],
                            ],
                            [
                                'name' => 'Components',
                                'blocks' => [
                                    ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=8oHfChSLCzM', 'duration' => 780],
                                    ['type' => 'text', 'content' => "## Blade Components\n\nComponents provide a way to create reusable UI elements. Laravel supports both class-based and anonymous components.\n\n### Creating a Component\n\n```bash\n# Create a component\nphp artisan make:component Alert\n\n# This creates:\n# - app/View/Components/Alert.php\n# - resources/views/components/alert.blade.php\n```"],
                                    ['type' => 'quiz', 'questions' => [
                                        ['question' => 'How do you use a component called Alert in Blade?', 'options' => ['<x-alert />', '@component(\'alert\')', '<Alert />', '{{ alert }}'], 'correct' => 0],
                                    ]],
                                ],
                            ],
                            [
                                'name' => 'Directives',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "## Blade Directives\n\nBlade provides many built-in directives for common tasks.\n\n### Common Directives\n\n```blade\n@foreach (\$items as \$item)\n    {{ \$item }}\n@endforeach\n\n@forelse (\$items as \$item)\n    {{ \$item }}\n@empty\n    No items found.\n@endforelse\n\n@auth\n    User is logged in\n@endauth\n\n@guest\n    User is a guest\n@endguest\n```"],
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
                                    ['type' => 'text', 'content' => "## Compound Components Pattern\n\nCompound components are a pattern where components work together to form a complete UI element while allowing flexible composition.\n\n### Example: Tabs Component\n\n```tsx\n<Tabs>\n  <Tabs.List>\n    <Tabs.Tab>Tab 1</Tabs.Tab>\n    <Tabs.Tab>Tab 2</Tabs.Tab>\n  </Tabs.List>\n  <Tabs.Panels>\n    <Tabs.Panel>Content 1</Tabs.Panel>\n    <Tabs.Panel>Content 2</Tabs.Panel>\n  </Tabs.Panels>\n</Tabs>\n```"],
                                    ['type' => 'assignment', 'language' => 'typescript', 'instructions' => "Create a compound Accordion component with:\n\n1. Accordion (parent)\n2. Accordion.Item\n3. Accordion.Header\n4. Accordion.Content\n\nUse React Context to share state between components.", 'starter_code' => "import { createContext, useContext, useState, ReactNode } from 'react';\n\nconst AccordionContext = createContext<{\n  activeIndex: number | null;\n  setActiveIndex: (index: number | null) => void;\n} | null>(null);\n\nexport function Accordion({ children }: { children: ReactNode }) {\n  const [activeIndex, setActiveIndex] = useState<number | null>(null);\n\n  return (\n    <AccordionContext.Provider value={{ activeIndex, setActiveIndex }}>\n      <div className=\"accordion\">{children}</div>\n    </AccordionContext.Provider>\n  );\n}\n\n// Add Accordion.Item, Accordion.Header, Accordion.Content\n"],
                                ],
                            ],
                            [
                                'name' => 'Render Props',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "## Render Props Pattern\n\nA render prop is a function prop that a component uses to know what to render. This pattern provides great flexibility for sharing code.\n\n### Basic Example\n\n```tsx\nfunction Mouse({ render }) {\n  const [position, setPosition] = useState({ x: 0, y: 0 });\n\n  // Track mouse position...\n\n  return render(position);\n}\n\n// Usage\n<Mouse render={({ x, y }) => (\n  <p>Mouse at: {x}, {y}</p>\n)} />\n```"],
                                    ['type' => 'quiz', 'questions' => [
                                        ['question' => 'What is the main benefit of the render props pattern?', 'options' => ['Sharing stateful logic between components', 'Better performance', 'Smaller bundle size', 'Type safety'], 'correct' => 0],
                                    ]],
                                ],
                            ],
                            [
                                'name' => 'Higher-Order Components',
                                'blocks' => [
                                    ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=B6aNv8nkUSw', 'duration' => 720],
                                    ['type' => 'text', 'content' => "## Higher-Order Components (HOCs)\n\nA HOC is a function that takes a component and returns a new component with enhanced functionality.\n\n### Example: withAuth HOC\n\n```tsx\nfunction withAuth(WrappedComponent) {\n  return function AuthenticatedComponent(props) {\n    const { user } = useAuth();\n\n    if (!user) {\n      return <Redirect to=\"/login\" />;\n    }\n\n    return <WrappedComponent {...props} user={user} />;\n  };\n}\n\n// Usage\nconst ProtectedPage = withAuth(Dashboard);\n```"],
                                    ['type' => 'assignment', 'language' => 'typescript', 'instructions' => "Create a withLoading HOC that:\n\n1. Shows a loading spinner while data is being fetched\n2. Passes the data to the wrapped component when ready\n3. Handles error states", 'starter_code' => "import { ComponentType, useState, useEffect } from 'react';\n\ninterface WithLoadingProps {\n  isLoading: boolean;\n  error: Error | null;\n}\n\nexport function withLoading<T extends object>(\n  WrappedComponent: ComponentType<T>,\n  fetchData: () => Promise<Partial<T>>\n) {\n  return function WithLoadingComponent(props: Omit<T, keyof WithLoadingProps>) {\n    const [isLoading, setIsLoading] = useState(true);\n    const [error, setError] = useState<Error | null>(null);\n    const [data, setData] = useState<Partial<T>>({});\n\n    useEffect(() => {\n      // Implement fetch logic here\n    }, []);\n\n    if (isLoading) return <div>Loading...</div>;\n    if (error) return <div>Error: {error.message}</div>;\n\n    return <WrappedComponent {...(props as T)} {...data} />;\n  };\n}\n"],
                                ],
                            ],
                            [
                                'name' => 'Custom Hooks',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "## Custom Hooks\n\nCustom hooks let you extract component logic into reusable functions. They're the modern alternative to HOCs and render props for sharing logic.\n\n### Example: useLocalStorage\n\n```tsx\nfunction useLocalStorage<T>(key: string, initialValue: T) {\n  const [storedValue, setStoredValue] = useState<T>(() => {\n    try {\n      const item = window.localStorage.getItem(key);\n      return item ? JSON.parse(item) : initialValue;\n    } catch {\n      return initialValue;\n    }\n  });\n\n  const setValue = (value: T) => {\n    setStoredValue(value);\n    window.localStorage.setItem(key, JSON.stringify(value));\n  };\n\n  return [storedValue, setValue] as const;\n}\n```"],
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
                                    ['type' => 'text', 'content' => "## React Context Deep Dive\n\nContext provides a way to pass data through the component tree without prop drilling. But it needs to be used carefully to avoid performance issues.\n\n### Best Practices\n\n- Split contexts by concern (auth, theme, user preferences)\n- Keep context values stable with useMemo\n- Consider context selectors for performance\n- Use multiple small contexts instead of one large one"],
                                ],
                            ],
                            [
                                'name' => 'useReducer Patterns',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "## useReducer for Complex State\n\nuseReducer is perfect for managing complex state logic. It's especially useful when state transitions depend on previous state.\n\n### Pattern: Action Creators\n\n```tsx\nconst actions = {\n  increment: () => ({ type: 'INCREMENT' }),\n  decrement: () => ({ type: 'DECREMENT' }),\n  reset: (value: number) => ({ type: 'RESET', payload: value }),\n};\n```"],
                                    ['type' => 'assignment', 'language' => 'typescript', 'instructions' => "Build a shopping cart reducer with:\n\n1. ADD_ITEM action\n2. REMOVE_ITEM action\n3. UPDATE_QUANTITY action\n4. CLEAR_CART action\n\nInclude proper TypeScript types.", 'starter_code' => "interface CartItem {\n  id: string;\n  name: string;\n  price: number;\n  quantity: number;\n}\n\ninterface CartState {\n  items: CartItem[];\n  total: number;\n}\n\ntype CartAction =\n  | { type: 'ADD_ITEM'; payload: Omit<CartItem, 'quantity'> }\n  | { type: 'REMOVE_ITEM'; payload: string }\n  | { type: 'UPDATE_QUANTITY'; payload: { id: string; quantity: number } }\n  | { type: 'CLEAR_CART' };\n\nfunction cartReducer(state: CartState, action: CartAction): CartState {\n  switch (action.type) {\n    case 'ADD_ITEM':\n      // Implement\n      return state;\n    // Add other cases\n    default:\n      return state;\n  }\n}\n"],
                                ],
                            ],
                            [
                                'name' => 'State Machines with XState',
                                'blocks' => [
                                    ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=iDZUKFDiMi4', 'duration' => 960],
                                    ['type' => 'text', 'content' => "## State Machines\n\nState machines provide a robust way to model complex UI states and transitions. XState is a popular library for this.\n\n### Benefits\n\n- Impossible states are truly impossible\n- Clear visualization of state flow\n- Predictable state transitions\n- Easy to test and debug"],
                                    ['type' => 'resources', 'links' => [
                                        ['title' => 'XState Documentation', 'url' => 'https://xstate.js.org/docs/'],
                                        ['title' => 'State Machine Visualizer', 'url' => 'https://stately.ai/viz'],
                                    ]],
                                ],
                            ],
                            [
                                'name' => 'Server State with React Query',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "## React Query (TanStack Query)\n\nReact Query makes fetching, caching, and updating server state simple and powerful.\n\n### Key Concepts\n\n- **Queries:** Fetch and cache data\n- **Mutations:** Create, update, delete data\n- **Query Invalidation:** Refetch stale data\n- **Optimistic Updates:** Update UI before server confirms"],
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
                                    ['type' => 'text', 'content' => "## Memoization in React\n\nMemoization prevents unnecessary re-renders and recalculations.\n\n### React.memo\n\nWraps a component to prevent re-renders if props haven't changed.\n\n### useMemo\n\nMemoizes expensive calculations between renders.\n\n```tsx\nconst expensiveValue = useMemo(() => {\n  return computeExpensiveValue(a, b);\n}, [a, b]);\n```"],
                                ],
                            ],
                            [
                                'name' => 'useCallback Best Practices',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "## useCallback\n\nuseCallback memoizes functions to maintain referential equality between renders.\n\n### When to Use\n\n- Passing callbacks to optimized child components\n- Dependencies for other hooks\n- Event handlers in effects\n\n### When NOT to Use\n\n- Simple components that don't need optimization\n- Callbacks that change every render anyway"],
                                    ['type' => 'quiz', 'questions' => [
                                        ['question' => 'When should you use useCallback?', 'options' => ['When passing callbacks to memoized children', 'Always for all functions', 'Never - it\'s deprecated', 'Only for async functions'], 'correct' => 0],
                                    ]],
                                ],
                            ],
                            [
                                'name' => 'Code Splitting',
                                'blocks' => [
                                    ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=JU6sl_yyZqs', 'duration' => 660],
                                    ['type' => 'text', 'content' => "## Code Splitting with React.lazy\n\nCode splitting lets you split your code into smaller chunks that are loaded on demand.\n\n### Example\n\n```tsx\nimport { lazy, Suspense } from 'react';\n\nconst HeavyComponent = lazy(() => import('./HeavyComponent'));\n\nfunction App() {\n  return (\n    <Suspense fallback={<Loading />}>\n      <HeavyComponent />\n    </Suspense>\n  );\n}\n```"],
                                    ['type' => 'assignment', 'language' => 'typescript', 'instructions' => "Implement route-based code splitting for:\n\n1. Dashboard page\n2. Settings page\n3. Profile page\n\nInclude proper loading states and error boundaries.", 'starter_code' => "import { lazy, Suspense } from 'react';\nimport { Routes, Route } from 'react-router-dom';\n\n// Lazy load the pages\nconst Dashboard = lazy(() => import('./pages/Dashboard'));\nconst Settings = lazy(() => import('./pages/Settings'));\nconst Profile = lazy(() => import('./pages/Profile'));\n\nfunction App() {\n  return (\n    <Suspense fallback={<div>Loading...</div>}>\n      <Routes>\n        <Route path=\"/\" element={<Dashboard />} />\n        {/* Add other routes */}\n      </Routes>\n    </Suspense>\n  );\n}\n"],
                                ],
                            ],
                            [
                                'name' => 'Virtual Lists',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "## Virtualizing Long Lists\n\nVirtual lists only render items that are visible in the viewport, dramatically improving performance for large datasets.\n\n### Popular Libraries\n\n- **react-window:** Lightweight virtualization\n- **react-virtuoso:** Feature-rich with auto-sizing\n- **TanStack Virtual:** Framework-agnostic solution"],
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
