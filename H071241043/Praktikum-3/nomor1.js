function countEvenNumbers(start, end) {
  let evenNumbers = [];
  let evenNumberSum = 0;

  for (let i = start; i <= end; i++) {
    if (i % 2 === 0) {
      evenNumbers.push(i);
      evenNumberSum += 1;
    }
  }

  return `${evenNumberSum} [${evenNumbers.join(", ")}]`;
}

console.log(countEvenNumbers(1, 10));
