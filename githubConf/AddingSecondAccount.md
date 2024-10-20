1. Generate a New SSH Key for the Second Account

Open Terminal.
Generate a new SSH key with a unique name to avoid overwriting your existing key:

ssh-keygen -t rsa -b 4096 -C "your_company_email@example.com"

When prompted for the file location, enter a unique name (e.g., ~/.ssh/id_rsa_company).
Optionally, set a passphrase when prompted.

2. Add the New SSH Key to the SSH Agent

Start the SSH agent if it’s not already running:

eval "$(ssh-agent -s)"

Add your new SSH private key to the agent:

ssh-add ~/.ssh/id_rsa_company

3. Copy the New SSH Key to Your Clipboard

Use the following command to copy the public key to your clipboard:

pbcopy < ~/.ssh/id_rsa_company.pub

4. Add the New SSH Key to Your GitHub Company Account

Log in to your company GitHub account.
Navigate to Settings (click on your profile icon).
In the left sidebar, click SSH and GPG keys.
Click the New SSH key button.
Paste your SSH key into the "Key" field and give it a descriptive title.
Click Add SSH key.

5. Configure the SSH Config File

To manage multiple SSH keys, create or edit the ~/.ssh/config file:

Open the SSH config file:

nano ~/.ssh/config

Add the following entries for each account:

Host github.com-company
    HostName github.com
    User git
    IdentityFile ~/.ssh/id_rsa_company

Host github.com
    HostName github.com
    User git
    IdentityFile ~/.ssh/id_rsa

6. Update the Remote URL in Your Local Repository

Navigate to your local repository directory:

cd /path/to/your/repo

Update the remote URL to use the new host for your company account:

git remote set-url origin git@github.com-company:company_username/repo.git

Replace company_username with your company GitHub username and repo.git with the name of your repository.

7. Test the SSH Connection

You can test the SSH connection for your company account with:

ssh -T git@github.com-company

You should see a message confirming successful authentication.

8. Push Changes

Now you can push changes to the repository using the company account:

git push origin branch-name

Additional Notes
Repeat these steps for any other repositories you want to access with your company account.

If you encounter any issues or need further assistance, let me know!