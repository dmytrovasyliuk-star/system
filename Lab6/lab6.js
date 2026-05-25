//Ex1
let a = 10;
let b = 5;

console.log("a + b =", a + b);
console.log("a - b =", a - b);
console.log("a * b =", a * b);
console.log("a / b =", a / b);

//Ex2
let firstName = "Іван ";
let lastName = "Іванов!";
let fullName = firstName + lastName;
console.log("Привіт, ", fullName);

//Ex3
let age = 20;
if (age >= 18) {
    console.log("Доступ дозволено.");
} else {
    console.log("Доступ заборонено.");
}
//Ex4
let i = 1;
for (i = 1; i <= 10; i++) {
    console.log("Поточне число: ", i);
}

//Ex5
function square(number) {
    return number * number;
}
let result = square(5);
console.log("Квадрат числа 5:", result);

//Ex 6
let fruits = ["Apple", "Banana", "Orange"];
fruits.push("Mango");
console.log("Масив фруктів:", fruits);