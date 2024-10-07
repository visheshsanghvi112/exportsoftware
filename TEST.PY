import time
import matplotlib.pyplot as plt

class Solution:
    def shortestPalindrome(self, s: str) -> str:
        # Step 1: Edge case handling
        if not s:
            return ""
        
        rev_s = s[::-1]
        combined = s + '#' + rev_s

        # Step 2: Compute LPS array
        lps = self.computeLPS(combined)

        # Step 3: Find the longest palindromic prefix
        palindromic_prefix_length = lps[-1]
        to_add = rev_s[:len(s) - palindromic_prefix_length]

        # Step 4: Return the shortest palindrome
        return to_add + s

    def computeLPS(self, pattern: str) -> list:
        lps = [0] * len(pattern)
        length = 0
        i = 1

        while i < len(pattern):
            if pattern[i] == pattern[length]:
                length += 1
                lps[i] = length
                i += 1
            else:
                if length != 0:
                    length = lps[length - 1]
                else:
                    lps[i] = 0
                    i += 1

        return lps

    def benchmark(self, test_cases):
        times = []
        for case in test_cases:
            start_time = time.time()
            self.shortestPalindrome(case)
            times.append(time.time() - start_time)
        return times

    def visualize_benchmark(self, times, test_cases):
        plt.figure(figsize=(10, 5))
        plt.plot(test_cases, times, marker='o')
        plt.title('Performance Benchmark of Shortest Palindrome')
        plt.xlabel('Input Size')
        plt.ylabel('Time (seconds)')
        plt.grid()
        plt.show()

# Example usage
solution = Solution()

# Test cases with varying lengths
test_cases = ['a', 'ab', 'aacecaaa', 'abcd', 'a' * 1000, 'b' * 1000 + 'a' * 1000]
times = solution.benchmark(test_cases)
solution.visualize_benchmark(times, [len(tc) for tc in test_cases])

# Print results for the test cases
for s in test_cases:
    print(f"Input: '{s}' -> Shortest Palindrome: '{solution.shortestPalindrome(s)}'")
