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
                'name' => 'Introduction to Java',
                'thumbnail' => 'https://picsum.photos/seed/java/640/480',
                'description' => "Learn the fundamentals of Java programming from scratch. This beginner-friendly course covers everything you need to know to start building applications with one of the world's most popular programming languages.\n\nYou'll learn about Java syntax, object-oriented programming concepts, and how to write your first Java programs. By the end of this course, you'll have a solid foundation in Java that will prepare you for more advanced topics.",
                'difficulty' => CourseDifficulty::Easy,
                'faqs' => [
                    ['question' => 'Do I need any prior programming experience?', 'answer' => 'No! This course is designed for complete beginners. We start from the very basics and build up your knowledge step by step.'],
                    ['question' => 'What software do I need?', 'answer' => 'You\'ll need the Java Development Kit (JDK) and a text editor or IDE like IntelliJ IDEA or VS Code. We cover the setup process in the course.'],
                    ['question' => 'Which Java version is covered?', 'answer' => 'This course uses Java 17 LTS, which is the current long-term support version widely used in production.'],
                ],
                'chapters' => [
                    [
                        'name' => 'Getting Started',
                        'lessons' => [
                            [
                                'name' => 'What is Java?',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "## Welcome to Java!\n\nJava is a high-level, class-based, object-oriented programming language that is designed to have as few implementation dependencies as possible.\n\n### Why Learn Java?\n\n- **Platform Independent:** Write once, run anywhere - Java code runs on any device with a JVM\n- **Enterprise Standard:** Powers millions of enterprise applications, Android apps, and backend services\n- **Strong Ecosystem:** Extensive libraries, frameworks (Spring, Hibernate), and tools\n- **Job Market:** Consistently one of the most in-demand programming languages\n\n### Java's Key Features\n\n1. **Object-Oriented:** Everything in Java is an object, making it easier to organize and maintain code\n2. **Strongly Typed:** Catches errors at compile time rather than runtime\n3. **Automatic Memory Management:** Garbage collection handles memory allocation and deallocation\n4. **Rich Standard Library:** Comprehensive APIs for networking, I/O, collections, and more"],
                                    ['type' => 'resources', 'links' => [
                                        ['title' => 'Official Java Documentation', 'url' => 'https://docs.oracle.com/en/java/'],
                                        ['title' => 'Java Tutorials by Oracle', 'url' => 'https://docs.oracle.com/javase/tutorial/'],
                                    ]],
                                ],
                            ],
                            [
                                'name' => 'Your First Java Program',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "## Writing Your First Java Program\n\nLet's write your very first Java program! By tradition, we'll start with a simple greeting.\n\n### Java Class Structure\n\nEvery Java program needs at least one class. The class name must match the filename. Methods contain the code that performs actions.\n\n### Your Task\n\nComplete the `greet()` method to return the string \"Hello, World!\". This is a classic first program that every programmer writes when learning a new language."],
                                    ['type' => 'assignment', 'language' => 'java', 'instructions' => "Complete the `greet()` method in the Main class to return \"Hello, World!\".\n\n**Requirements:**\n1. The method should return a String\n2. The returned string must be exactly \"Hello, World!\"\n3. Pay attention to capitalization and punctuation", 'starter_code' => 'public class Main {
    public static void main(String[] args) {
        Main program = new Main();
        System.out.println(program.greet());
    }

    public String greet() {
        // TODO: Return "Hello, World!"
        return "";
    }
}
', 'solution' => 'public class Main {
    public static void main(String[] args) {
        Main program = new Main();
        System.out.println(program.greet());
    }

    public String greet() {
        return "Hello, World!";
    }
}
', 'test' => [
                                        'class_name' => 'SolutionTest',
                                        'file_content' => <<<'JUNIT'
import org.junit.jupiter.api.Test;
import static org.junit.jupiter.api.Assertions.*;

class SolutionTest {
    @Test
    void testGreetReturnsHelloWorld() {
        Main main = new Main();
        assertEquals("Hello, World!", main.greet());
    }
}
JUNIT
                                    ]],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'Variables and Data Types',
                        'lessons' => [
                            [
                                'name' => 'Primitive Data Types',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "## Java Primitive Data Types\n\nJava has 8 primitive data types that form the foundation of all data manipulation.\n\n### Integer Types\n- **byte:** 8-bit signed integer (-128 to 127)\n- **short:** 16-bit signed integer (-32,768 to 32,767)\n- **int:** 32-bit signed integer (most commonly used)\n- **long:** 64-bit signed integer (use `L` suffix: `100L`)\n\n### Floating-Point Types\n- **float:** 32-bit floating point (use `f` suffix: `3.14f`)\n- **double:** 64-bit floating point (default for decimals)\n\n### Other Types\n- **boolean:** `true` or `false`\n- **char:** Single 16-bit Unicode character (use single quotes: `'A'`)\n\n### Examples\nint age = 25;\ndouble price = 19.99;\nboolean isActive = true;\nchar grade = 'A';"],
                                    ['type' => 'quiz', 'questions' => [
                                        ['question' => 'Which primitive type would you use to store a person\'s age?', 'options' => ['int', 'double', 'String', 'boolean'], 'correct' => 0],
                                        ['question' => 'What suffix is required for a float literal?', 'options' => ['f', 'd', 'l', 'No suffix needed'], 'correct' => 0],
                                    ]],
                                ],
                            ],
                            [
                                'name' => 'Working with Variables',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "## Declaring and Using Variables\n\nVariables are containers for storing data values. In Java, you must declare the type of a variable before using it.\n\n### Variable Declaration\n// Declaration only\nint count;\n\n// Declaration with initialization\nint score = 100;\n\n// Multiple declarations\nint x = 1, y = 2, z = 3;\n\n### Naming Conventions\n- Start with a letter, underscore, or dollar sign\n- Use camelCase for variable names: `firstName`, `totalAmount`\n- Constants use UPPER_SNAKE_CASE: `MAX_VALUE`\n- Names are case-sensitive: `age` and `Age` are different"],
                                    ['type' => 'assignment', 'language' => 'java', 'instructions' => "Complete the `calculateSum()` method to add two integers and return the result.\n\n**Requirements:**\n1. The method takes two int parameters: `a` and `b`\n2. Return the sum of both numbers", 'starter_code' => 'public class Main {
    public static void main(String[] args) {
        Main program = new Main();
        System.out.println("5 + 3 = " + program.calculateSum(5, 3));
    }

    public int calculateSum(int a, int b) {
        // TODO: Return the sum of a and b
        return 0;
    }
}
', 'solution' => 'public class Main {
    public static void main(String[] args) {
        Main program = new Main();
        System.out.println("5 + 3 = " + program.calculateSum(5, 3));
    }

    public int calculateSum(int a, int b) {
        return a + b;
    }
}
', 'test' => [
                                        'class_name' => 'SolutionTest',
                                        'file_content' => <<<'JUNIT'
import org.junit.jupiter.api.Test;
import static org.junit.jupiter.api.Assertions.*;

class SolutionTest {
    @Test
    void testCalculateSumPositiveNumbers() {
        Main main = new Main();
        assertEquals(8, main.calculateSum(5, 3));
    }

    @Test
    void testCalculateSumWithZero() {
        Main main = new Main();
        assertEquals(5, main.calculateSum(5, 0));
    }

    @Test
    void testCalculateSumNegativeNumbers() {
        Main main = new Main();
        assertEquals(-8, main.calculateSum(-5, -3));
    }
}
JUNIT
                                    ]],
                                ],
                            ],
                            [
                                'name' => 'Strings in Java',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "## Working with Strings\n\nStrings in Java are objects, not primitives. They represent sequences of characters and are immutable (cannot be changed after creation).\n\n### Creating Strings\n// String literal (preferred)\nString name = \"John\";\n\n// Using new keyword\nString greeting = new String(\"Hello\");\n\n### Common String Methods\nString s = \"Hello World\";\n\ns.length()           // 11\ns.toUpperCase()      // \"HELLO WORLD\"\ns.toLowerCase()      // \"hello world\"\ns.charAt(0)          // 'H'\ns.substring(0, 5)    // \"Hello\"\ns.contains(\"World\")  // true\ns.replace(\"World\", \"Java\")  // \"Hello Java\"\n\n### String Concatenation\nString first = \"Hello\";\nString second = \"World\";\nString result = first + \" \" + second;  // \"Hello World\""],
                                    ['type' => 'assignment', 'language' => 'java', 'instructions' => "Complete the `formatName()` method to format a name properly.\n\n**Requirements:**\n1. Take a name string as input\n2. Return the name with the first letter capitalized and the rest lowercase\n3. Example: \"jOHN\" should become \"John\"", 'starter_code' => 'public class Main {
    public static void main(String[] args) {
        Main program = new Main();
        System.out.println(program.formatName("jOHN"));
    }

    public String formatName(String name) {
        // TODO: Capitalize first letter, lowercase the rest
        // Hint: Use substring(), toUpperCase(), and toLowerCase()
        return "";
    }
}
', 'solution' => 'public class Main {
    public static void main(String[] args) {
        Main program = new Main();
        System.out.println(program.formatName("jOHN"));
    }

    public String formatName(String name) {
        if (name == null || name.isEmpty()) {
            return name;
        }
        return name.substring(0, 1).toUpperCase() + name.substring(1).toLowerCase();
    }
}
', 'test' => [
                                        'class_name' => 'SolutionTest',
                                        'file_content' => <<<'JUNIT'
import org.junit.jupiter.api.Test;
import static org.junit.jupiter.api.Assertions.*;

class SolutionTest {
    @Test
    void testFormatNameAllCaps() {
        Main main = new Main();
        assertEquals("John", main.formatName("JOHN"));
    }

    @Test
    void testFormatNameMixedCase() {
        Main main = new Main();
        assertEquals("John", main.formatName("jOHN"));
    }

    @Test
    void testFormatNameAllLowercase() {
        Main main = new Main();
        assertEquals("Mary", main.formatName("mary"));
    }
}
JUNIT
                                    ]],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'Control Flow',
                        'lessons' => [
                            [
                                'name' => 'If-Else Statements',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "## Conditional Statements\n\nControl flow statements allow your program to make decisions based on conditions.\n\n### Basic If Statement\nif (condition) {\n    // code to execute if condition is true\n}\n\n### If-Else Statement\nif (score >= 60) {\n    System.out.println(\"Pass\");\n} else {\n    System.out.println(\"Fail\");\n}\n\n### If-Else-If Chain\nif (score >= 90) {\n    grade = 'A';\n} else if (score >= 80) {\n    grade = 'B';\n} else if (score >= 70) {\n    grade = 'C';\n} else {\n    grade = 'F';\n}\n\n### Comparison Operators\n- `==` equal to\n- `!=` not equal to\n- `>` greater than\n- `<` less than\n- `>=` greater than or equal\n- `<=` less than or equal"],
                                    ['type' => 'assignment', 'language' => 'java', 'instructions' => "Complete the `getGrade()` method to return a letter grade based on a numeric score.\n\n**Grading Scale:**\n- 90-100: \"A\"\n- 80-89: \"B\"\n- 70-79: \"C\"\n- 60-69: \"D\"\n- Below 60: \"F\"", 'starter_code' => 'public class Main {
    public static void main(String[] args) {
        Main program = new Main();
        System.out.println("Score 85: " + program.getGrade(85));
        System.out.println("Score 72: " + program.getGrade(72));
        System.out.println("Score 55: " + program.getGrade(55));
    }

    public String getGrade(int score) {
        // TODO: Return the letter grade based on score
        return "";
    }
}
', 'solution' => 'public class Main {
    public static void main(String[] args) {
        Main program = new Main();
        System.out.println("Score 85: " + program.getGrade(85));
        System.out.println("Score 72: " + program.getGrade(72));
        System.out.println("Score 55: " + program.getGrade(55));
    }

    public String getGrade(int score) {
        if (score >= 90) {
            return "A";
        } else if (score >= 80) {
            return "B";
        } else if (score >= 70) {
            return "C";
        } else if (score >= 60) {
            return "D";
        } else {
            return "F";
        }
    }
}
', 'test' => [
                                        'class_name' => 'SolutionTest',
                                        'file_content' => <<<'JUNIT'
import org.junit.jupiter.api.Test;
import static org.junit.jupiter.api.Assertions.*;

class SolutionTest {
    @Test
    void testGradeA() {
        Main main = new Main();
        assertEquals("A", main.getGrade(95));
        assertEquals("A", main.getGrade(90));
    }

    @Test
    void testGradeB() {
        Main main = new Main();
        assertEquals("B", main.getGrade(85));
        assertEquals("B", main.getGrade(80));
    }

    @Test
    void testGradeC() {
        Main main = new Main();
        assertEquals("C", main.getGrade(75));
    }

    @Test
    void testGradeD() {
        Main main = new Main();
        assertEquals("D", main.getGrade(65));
    }

    @Test
    void testGradeF() {
        Main main = new Main();
        assertEquals("F", main.getGrade(55));
        assertEquals("F", main.getGrade(0));
    }
}
JUNIT
                                    ]],
                                ],
                            ],
                            [
                                'name' => 'Loops',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "## Loops in Java\n\nLoops allow you to execute a block of code multiple times.\n\n### For Loop\nBest when you know how many times to iterate:\nfor (int i = 0; i < 5; i++) {\n    System.out.println(i);  // Prints 0, 1, 2, 3, 4\n}\n\n### While Loop\nBest when the number of iterations is unknown:\nint count = 0;\nwhile (count < 5) {\n    System.out.println(count);\n    count++;\n}\n\n### Do-While Loop\nExecutes at least once:\nint num = 0;\ndo {\n    System.out.println(num);\n    num++;\n} while (num < 5);\n\n### Loop Control\n- `break` - exits the loop immediately\n- `continue` - skips to the next iteration"],
                                    ['type' => 'assignment', 'language' => 'java', 'instructions' => "Complete the `factorial()` method to calculate the factorial of a number.\n\n**Factorial Definition:**\n- factorial(5) = 5 × 4 × 3 × 2 × 1 = 120\n- factorial(0) = 1\n- factorial(1) = 1\n\nUse a loop to calculate the result.", 'starter_code' => 'public class Main {
    public static void main(String[] args) {
        Main program = new Main();
        System.out.println("5! = " + program.factorial(5));
        System.out.println("0! = " + program.factorial(0));
    }

    public int factorial(int n) {
        // TODO: Calculate factorial using a loop
        return 0;
    }
}
', 'solution' => 'public class Main {
    public static void main(String[] args) {
        Main program = new Main();
        System.out.println("5! = " + program.factorial(5));
        System.out.println("0! = " + program.factorial(0));
    }

    public int factorial(int n) {
        int result = 1;
        for (int i = 2; i <= n; i++) {
            result *= i;
        }
        return result;
    }
}
', 'test' => [
                                        'class_name' => 'SolutionTest',
                                        'file_content' => <<<'JUNIT'
import org.junit.jupiter.api.Test;
import static org.junit.jupiter.api.Assertions.*;

class SolutionTest {
    @Test
    void testFactorialOfZero() {
        Main main = new Main();
        assertEquals(1, main.factorial(0));
    }

    @Test
    void testFactorialOfOne() {
        Main main = new Main();
        assertEquals(1, main.factorial(1));
    }

    @Test
    void testFactorialOfFive() {
        Main main = new Main();
        assertEquals(120, main.factorial(5));
    }

    @Test
    void testFactorialOfTen() {
        Main main = new Main();
        assertEquals(3628800, main.factorial(10));
    }
}
JUNIT
                                    ]],
                                ],
                            ],
                            [
                                'name' => 'FizzBuzz Challenge',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "## The FizzBuzz Challenge\n\nFizzBuzz is a classic programming challenge often used in interviews. It tests your understanding of loops and conditionals.\n\n### The Rules\nFor numbers 1 to n:\n- If divisible by 3, print \"Fizz\"\n- If divisible by 5, print \"Buzz\"\n- If divisible by both 3 and 5, print \"FizzBuzz\"\n- Otherwise, print the number\n\n### Modulo Operator\nThe % operator returns the remainder of division:\n10 % 3  // returns 1\n15 % 5  // returns 0 (divisible)\n15 % 3  // returns 0 (divisible)"],
                                    ['type' => 'assignment', 'language' => 'java', 'instructions' => "Complete the `fizzBuzz()` method that takes a number and returns the appropriate string.\n\n**Rules:**\n- Return \"FizzBuzz\" if divisible by both 3 and 5\n- Return \"Fizz\" if divisible by 3 only\n- Return \"Buzz\" if divisible by 5 only\n- Return the number as a string otherwise", 'starter_code' => 'public class Main {
    public static void main(String[] args) {
        Main program = new Main();
        for (int i = 1; i <= 15; i++) {
            System.out.println(program.fizzBuzz(i));
        }
    }

    public String fizzBuzz(int n) {
        // TODO: Implement FizzBuzz logic
        return "";
    }
}
', 'solution' => 'public class Main {
    public static void main(String[] args) {
        Main program = new Main();
        for (int i = 1; i <= 15; i++) {
            System.out.println(program.fizzBuzz(i));
        }
    }

    public String fizzBuzz(int n) {
        if (n % 3 == 0 && n % 5 == 0) {
            return "FizzBuzz";
        } else if (n % 3 == 0) {
            return "Fizz";
        } else if (n % 5 == 0) {
            return "Buzz";
        } else {
            return String.valueOf(n);
        }
    }
}
', 'test' => [
                                        'class_name' => 'SolutionTest',
                                        'file_content' => <<<'JUNIT'
import org.junit.jupiter.api.Test;
import static org.junit.jupiter.api.Assertions.*;

class SolutionTest {
    @Test
    void testFizzBuzz() {
        Main main = new Main();
        assertEquals("FizzBuzz", main.fizzBuzz(15));
        assertEquals("FizzBuzz", main.fizzBuzz(30));
    }

    @Test
    void testFizz() {
        Main main = new Main();
        assertEquals("Fizz", main.fizzBuzz(3));
        assertEquals("Fizz", main.fizzBuzz(9));
    }

    @Test
    void testBuzz() {
        Main main = new Main();
        assertEquals("Buzz", main.fizzBuzz(5));
        assertEquals("Buzz", main.fizzBuzz(10));
    }

    @Test
    void testRegularNumbers() {
        Main main = new Main();
        assertEquals("1", main.fizzBuzz(1));
        assertEquals("7", main.fizzBuzz(7));
    }
}
JUNIT
                                    ]],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'Arrays and Methods',
                        'lessons' => [
                            [
                                'name' => 'Introduction to Arrays',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "## Arrays in Java\n\nArrays are containers that hold a fixed number of values of a single type.\n\n### Declaring Arrays\n// Declaration and initialization\nint[] numbers = {1, 2, 3, 4, 5};\n\n// Declaration with size\nint[] scores = new int[10];  // 10 elements, all 0\n\n// Declaration then assignment\nString[] names;\nnames = new String[3];\n\n### Accessing Elements\nArrays are zero-indexed:\nint[] arr = {10, 20, 30};\narr[0]  // 10 (first element)\narr[2]  // 30 (last element)\narr.length  // 3 (size of array)\n\n### Iterating Arrays\n// Traditional for loop\nfor (int i = 0; i < arr.length; i++) {\n    System.out.println(arr[i]);\n}\n\n// Enhanced for-each loop\nfor (int num : arr) {\n    System.out.println(num);\n}"],
                                    ['type' => 'assignment', 'language' => 'java', 'instructions' => "Complete the `findMax()` method to find the largest number in an array.\n\n**Requirements:**\n1. Return the maximum value in the array\n2. Assume the array has at least one element", 'starter_code' => 'public class Main {
    public static void main(String[] args) {
        Main program = new Main();
        int[] numbers = {3, 7, 2, 9, 1, 5};
        System.out.println("Max: " + program.findMax(numbers));
    }

    public int findMax(int[] arr) {
        // TODO: Find and return the maximum value
        return 0;
    }
}
', 'solution' => 'public class Main {
    public static void main(String[] args) {
        Main program = new Main();
        int[] numbers = {3, 7, 2, 9, 1, 5};
        System.out.println("Max: " + program.findMax(numbers));
    }

    public int findMax(int[] arr) {
        int max = arr[0];
        for (int i = 1; i < arr.length; i++) {
            if (arr[i] > max) {
                max = arr[i];
            }
        }
        return max;
    }
}
', 'test' => [
                                        'class_name' => 'SolutionTest',
                                        'file_content' => <<<'JUNIT'
import org.junit.jupiter.api.Test;
import static org.junit.jupiter.api.Assertions.*;

class SolutionTest {
    @Test
    void testFindMaxMiddle() {
        Main main = new Main();
        assertEquals(9, main.findMax(new int[]{3, 7, 2, 9, 1, 5}));
    }

    @Test
    void testFindMaxFirst() {
        Main main = new Main();
        assertEquals(10, main.findMax(new int[]{10, 5, 3, 1}));
    }

    @Test
    void testFindMaxLast() {
        Main main = new Main();
        assertEquals(100, main.findMax(new int[]{1, 2, 3, 100}));
    }

    @Test
    void testFindMaxSingleElement() {
        Main main = new Main();
        assertEquals(42, main.findMax(new int[]{42}));
    }

    @Test
    void testFindMaxNegativeNumbers() {
        Main main = new Main();
        assertEquals(-1, main.findMax(new int[]{-5, -1, -10}));
    }
}
JUNIT
                                    ]],
                                ],
                            ],
                            [
                                'name' => 'Array Sum and Average',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "## Working with Array Data\n\nCommon operations on arrays include calculating sums, averages, and other statistics.\n\n### Calculating Sum\nint sum = 0;\nfor (int num : numbers) {\n    sum += num;\n}\n\n### Calculating Average\ndouble average = (double) sum / numbers.length;\n\n### Note on Division\nIn Java, dividing two integers gives an integer result:\n5 / 2      // equals 2 (integer division)\n5.0 / 2    // equals 2.5\n(double) 5 / 2  // equals 2.5 (casting)"],
                                    ['type' => 'assignment', 'language' => 'java', 'instructions' => "Complete the `calculateAverage()` method to find the average of numbers in an array.\n\n**Requirements:**\n1. Return the average as a double\n2. Handle the calculation correctly to avoid integer division", 'starter_code' => 'public class Main {
    public static void main(String[] args) {
        Main program = new Main();
        int[] numbers = {10, 20, 30, 40, 50};
        System.out.println("Average: " + program.calculateAverage(numbers));
    }

    public double calculateAverage(int[] arr) {
        // TODO: Calculate and return the average
        return 0.0;
    }
}
', 'solution' => 'public class Main {
    public static void main(String[] args) {
        Main program = new Main();
        int[] numbers = {10, 20, 30, 40, 50};
        System.out.println("Average: " + program.calculateAverage(numbers));
    }

    public double calculateAverage(int[] arr) {
        int sum = 0;
        for (int num : arr) {
            sum += num;
        }
        return (double) sum / arr.length;
    }
}
', 'test' => [
                                        'class_name' => 'SolutionTest',
                                        'file_content' => <<<'JUNIT'
import org.junit.jupiter.api.Test;
import static org.junit.jupiter.api.Assertions.*;

class SolutionTest {
    @Test
    void testAverageWholeNumber() {
        Main main = new Main();
        assertEquals(30.0, main.calculateAverage(new int[]{10, 20, 30, 40, 50}), 0.001);
    }

    @Test
    void testAverageDecimal() {
        Main main = new Main();
        assertEquals(2.5, main.calculateAverage(new int[]{1, 2, 3, 4}), 0.001);
    }

    @Test
    void testAverageSingleElement() {
        Main main = new Main();
        assertEquals(42.0, main.calculateAverage(new int[]{42}), 0.001);
    }
}
JUNIT
                                    ]],
                                ],
                            ],
                            [
                                'name' => 'Reverse an Array',
                                'blocks' => [
                                    ['type' => 'text', 'content' => "## Array Manipulation\n\nReversing an array is a common programming task that tests your understanding of array indexing and loops.\n\n### Strategy\nTo reverse an array in-place:\n1. Use two pointers: one at the start, one at the end\n2. Swap elements at these positions\n3. Move pointers toward the center\n4. Stop when they meet or cross\n\n### Swapping Values\n// Swap arr[i] and arr[j]\nint temp = arr[i];\narr[i] = arr[j];\narr[j] = temp;"],
                                    ['type' => 'assignment', 'language' => 'java', 'instructions' => "Complete the `reverseArray()` method to reverse an array of integers.\n\n**Requirements:**\n1. Return a new array with elements in reverse order\n2. Do not modify the original array\n\n**Example:** `{1, 2, 3, 4}` becomes `{4, 3, 2, 1}`", 'starter_code' => 'public class Main {
    public static void main(String[] args) {
        Main program = new Main();
        int[] original = {1, 2, 3, 4, 5};
        int[] reversed = program.reverseArray(original);

        System.out.print("Reversed: ");
        for (int num : reversed) {
            System.out.print(num + " ");
        }
    }

    public int[] reverseArray(int[] arr) {
        // TODO: Return a new array with elements reversed
        return new int[0];
    }
}
', 'solution' => 'public class Main {
    public static void main(String[] args) {
        Main program = new Main();
        int[] original = {1, 2, 3, 4, 5};
        int[] reversed = program.reverseArray(original);

        System.out.print("Reversed: ");
        for (int num : reversed) {
            System.out.print(num + " ");
        }
    }

    public int[] reverseArray(int[] arr) {
        int[] result = new int[arr.length];
        for (int i = 0; i < arr.length; i++) {
            result[i] = arr[arr.length - 1 - i];
        }
        return result;
    }
}
', 'test' => [
                                        'class_name' => 'SolutionTest',
                                        'file_content' => <<<'JUNIT'
import org.junit.jupiter.api.Test;
import static org.junit.jupiter.api.Assertions.*;

class SolutionTest {
    @Test
    void testReverseArray() {
        Main main = new Main();
        assertArrayEquals(new int[]{5, 4, 3, 2, 1}, main.reverseArray(new int[]{1, 2, 3, 4, 5}));
    }

    @Test
    void testReverseArrayTwoElements() {
        Main main = new Main();
        assertArrayEquals(new int[]{2, 1}, main.reverseArray(new int[]{1, 2}));
    }

    @Test
    void testReverseArraySingleElement() {
        Main main = new Main();
        assertArrayEquals(new int[]{42}, main.reverseArray(new int[]{42}));
    }

    @Test
    void testOriginalUnchanged() {
        Main main = new Main();
        int[] original = {1, 2, 3};
        main.reverseArray(original);
        assertArrayEquals(new int[]{1, 2, 3}, original);
    }
}
JUNIT
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
     * Create an assignment with optional test.
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
            'language' => $blockData['language'] ?? 'java',
        ]);

        if (isset($blockData['test'])) {
            BlockAssignmentTest::create([
                'block_assignment_id' => $assignment->id,
                'file_content' => $blockData['test']['file_content'],
                'class_name' => $blockData['test']['class_name'],
            ]);
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
