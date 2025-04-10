# Bek - Simple Version Control System

## Overview

Bek is a lightweight version control system designed to work seamlessly within PHP projects using Composer. It provides basic version tracking functionality for text files, making it easy to manage file changes in your projects.

## Requirements

- PHP 7.4 or higher
- Composer 2.0+
- Project using Composer (Laravel, Symfony, Yii, etc.)

## Installation

Install Bek in your project using Composer:

```bash
composer require bekhruz/bek
```

After installation, run the Bek installer:

```bash
php vendor/bekhruz/bek/install.php
```

### Installation Notes

- The installer attempts to create a global symlink for the `bek` command
- If symlink creation fails, manual installation instructions will be provided

## Usage

### Initialize Repository

Initialize version control in your project:

```bash
bek init
```

This creates a `.vcs` directory to store commit history and snapshots.

### Commit Files

Commit a text file:

```bash
bek commit file_name.txt
```

**Limitations:**
- Currently supports only .txt files
- Commits the current snapshot of the file

### View Commit History

View all commits for a file:

```bash
bek log file_name.txt
```

### Revert File to Previous Commit

Revert a file to a specific commit:

```bash
bek revert commit_id file_name.txt
```

## Project Structure

When you run `bek init`, it creates the following structure:

```
project_root/
└── .vcs/
    ├── commits/
    │   ├── commit1_id.txt
    │   └── commit1_id.json
    └── log.txt
```

## Best Practices

- Only use with text files
- Initialize Bek in the root of your project
- Commit files regularly
- Keep track of commit IDs for reverting


## Limitations

- Text files only
- No branching support
- Simplified version control
- Meant for small projects and learning

## Future Roadmap

- Support for multiple file types
- Branching
- More advanced version control features

## License

MIT License

And by the way, let's connect for more projects like this

## Contributing

Contributions are welcome! Please submit pull requests or open issues on the GitHub repository.

## Support

If you encounter any issues, please open a ticket on the project's issue tracker.
