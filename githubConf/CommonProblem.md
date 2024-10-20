** Not Creating the id_rsa_company file **

If the command to generate the SSH key isn't creating the id_rsa_company file, here are some steps to troubleshoot and ensure it’s done correctly:

1. Check Command Syntax

Make sure you’re using the correct command to generate the SSH key:

ssh-keygen -t rsa -b 4096 -C "your_company_email@example.com"

When prompted to enter a file in which to save the key, you should specify the full path like this:

Enter file in which to save the key (/Users/your_username/.ssh/id_rsa):
Instead of pressing Enter, type the following and then press Enter:

/Users/your_username/.ssh/id_rsa_company
(Replace your_username with your actual macOS username.)

2. Check for Existing Keys

If the key was generated but you can’t find it, check if it’s already there. Run:

ls ~/.ssh/

Look for any existing keys like id_rsa_company or id_rsa_company.pub.