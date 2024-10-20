1. Generate an SSH Key

If you don't already have an SSH key, you can generate one:

Open Terminal.
Run the following command, replacing "your_email@example.com" with your email address:

ssh-keygen -t rsa -b 4096 -C "your_email@example.com"

When prompted to enter a file in which to save the key, you can press Enter to accept the default location (~/.ssh/id_rsa) or specify a different name (e.g., ~/.ssh/id_rsa_github).
You may also be prompted to enter a passphrase. You can choose to set one or leave it empty for no passphrase.

2. Add the SSH Key to the SSH Agent

Start the SSH agent in the background:

eval "$(ssh-agent -s)"

Add your SSH private key to the SSH agent:

ssh-add ~/.ssh/id_rsa

(Replace ~/.ssh/id_rsa with your specific key name if you chose a different one.)

3. Copy the SSH Key to Your Clipboard

Use the following command to copy the public key to your clipboard:

pbcopy < ~/.ssh/id_rsa.pub

4. Add the SSH Key to Your GitHub Account

Go to GitHub and log in.
Navigate to Settings (click on your profile icon in the top right).
In the left sidebar, click SSH and GPG keys.
Click the New SSH key button.
Paste your SSH key into the "Key" field and give it a descriptive title.
Click Add SSH key.

5. Update the Remote URL in Your Local Repository

Navigate to your local repository directory:

cd /path/to/your/repo

Set the remote URL to use SSH:

git remote set-url origin git@github.com:username/repo.git

Replace username with your GitHub username and repo.git with the name of your repository.

6. Test the SSH Connection

You can test your SSH connection to GitHub with:

ssh -T git@github.com

If everything is set up correctly, you should see a message like:

Hi username! You've successfully authenticated, but GitHub does not provide shell access.

7. Push Changes

Now you can push your changes to the repository using SSH:

git push origin branch-name

Additional Notes
If you have multiple GitHub accounts, you may need to configure your ~/.ssh/config file to manage multiple SSH keys.

If you need help with that or anything else, let me know!