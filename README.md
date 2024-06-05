# Online-Chat-With-encode
A Cryptography-Focused Web Chat Application

This project represents a web-based chatting application built with a strong emphasis on user privacy and security through the implementation of cryptographic techniques like caesar, atbash, additive, and multiplicative in a sequenced manner. 
This document provides a visual overview of the application's interface and its core functionalities. 

Login/Signup Page
This is the initial page users encounter. It provides options to either log in with existing credentials or sign up to create a new account.
Login form with fields for Email and password.
Signup form with fields for creating a new name, Email, and password.

 


 


Main Chat Interface

This is the welcoming page where the user can search for other users to start a conversation.

 


When finding a user it goes to the conversation page where both participants can interact 
Through messages, it’s also possible to send messages to offline users 
Because all chats are saved into the database of the application 

  
How the app works
A Brief Description of Additive, Atbash, and Multiplicative Ciphers
These are three classic examples of substitution ciphers, where each letter in the plaintext (original message) is replaced by another letter or symbol to form the ciphertext (encrypted message) the app uses them all to encrypt user messages to get the best encryption possible.

1. Additive Cipher (Caesar Cipher): Method: Shifts each letter in the alphabet a fixed number of positions down the alphabet.
Example: With a shift of 3, A becomes D, B becomes E, and so on.
Key: The key is the number of positions to shift (e.g., 3 in the example).
Security: Very weak, easily cracked by trying all possible shifts (brute force).

2. Atbash Cipher: Method: Reverses the alphabet. The first letter becomes the last, the second becomes the second to last, and so on.
Example: A becomes Z, B becomes Y, and so on.
Key: No key is needed, as the substitution pattern is fixed.
Security: Weak, as the simple reversal is easy to recognize and decrypt.

3. Multiplicative Cipher: Method: Multiplies the numerical equivalent of each letter (A=1, B=2, etc.) by a fixed number (key) modulo the number of letters in the alphabet.
Example: Using a key of 3 (and assuming a 26-letter alphabet), A (1) becomes D (4) because (1 * 3) % 26 = 4.
Key: The key is the multiplier number (e.g., 3 in the example).
Security: More secure than additive, but still vulnerable to certain attacks, especially if the key shares factors with the alphabet size.

Comparison: Security: Multiplicative is generally the most secure among the three.
 
How data is stored

Passwords are encrypted with caesar cipher
  



Messages are encrypted using all of the three techniques.
 


