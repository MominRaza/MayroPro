# Mayro Pro

[![Release](https://img.shields.io/badge/release-v4.4.0-blue.svg?style=flat)](https://www.question2answer.org/qa/89985/)
[![Q2A](https://img.shields.io/badge/Question2Answer-v1.8.6-blue.svg?style=flat)](https://github.com/q2a/question2answer/releases)

Google Material Design & PWA ready Theme for [Question2Answer ( Q2A )].

## Features

- Google Material Design
- Mobile first, clean content focused, responsive layout designed for redability
- Easy to install and setup
- Developer friendly
- Dark Theme Support with three options
  - System default
  - light mode
  - dark mode
- Progressive Web App (PWA) support
  - Installable
  - Fast and reliable
  - PWA Optimized
- Multilingual support
- Right to Left Support

## Price

~80$~ free or ~5900₹~ free (INR)

## Screenshots

### Pixle 2 XL

#### App Icon & Splash Screen (Progressive Web App)

<p float="left">
  <img src="https://raw.githubusercontent.com/MominRaza/assets/main/Mayro%20Pro%20Mobile/6.png" width="300" alt="App Icon"/>
  <img src="https://raw.githubusercontent.com/MominRaza/assets/main/Mayro%20Pro%20Mobile/5.png" width="300" alt="Splash Screen"/>
</p>

#### Recent Questions

<p float="left">
  <img src="https://raw.githubusercontent.com/MominRaza/assets/main/Mayro%20Pro%20Mobile/1.png" width="300" alt="Recent Questions"/>
  <img src="https://raw.githubusercontent.com/MominRaza/assets/main/Mayro%20Pro%20Mobile/2.png" width="300" alt="Recent Question"/>
</p>

#### Question View

<p float="left">
  <img src="https://raw.githubusercontent.com/MominRaza/assets/main/Mayro%20Pro%20Mobile/4.png" width="300" alt="Question View"/>
  <img src="https://raw.githubusercontent.com/MominRaza/assets/main/Mayro%20Pro%20Mobile/3.png" width="300" alt="Question View"/>
</p>

#### App Drawer

<p float="left">
  <img src="https://raw.githubusercontent.com/MominRaza/assets/main/Mayro%20Pro%20Mobile/10.png" width="300" alt="App Drawer"/>
  <img src="https://raw.githubusercontent.com/MominRaza/assets/main/Mayro%20Pro%20Mobile/9.png" width="300" alt="App Drawer"/>
</p>

#### Users

<p float="left">
  <img src="https://raw.githubusercontent.com/MominRaza/assets/main/Mayro%20Pro%20Mobile/7.png" width="300" alt="Users"/>
  <img src="https://raw.githubusercontent.com/MominRaza/assets/main/Mayro%20Pro%20Mobile/8.png" width="300" alt="Users"/>
</p>

#### User Menu

<p float="left">
  <img src="https://raw.githubusercontent.com/MominRaza/assets/main/Mayro%20Pro%20Mobile/11.png" width="300" alt="User Menu"/>
  <img src="https://raw.githubusercontent.com/MominRaza/assets/main/Mayro%20Pro%20Mobile/12.png" width="300" alt="User Menu"/>
</p>

### iPad / iPad Pro

#### Q&A

<p float="left">
  <img src="https://raw.githubusercontent.com/MominRaza/assets/main/Mayro%20Pro/2.png" width="700" alt="Q&A"/>
  <img src="https://raw.githubusercontent.com/MominRaza/assets/main/Mayro%20Pro/1.png" width="700" alt="Q&A"/>
</p>

#### User Menu

<p float="left">
  <img src="https://raw.githubusercontent.com/MominRaza/assets/main/Mayro%20Pro/4.png" width="400" alt="User Menu"/>
  <img src="https://raw.githubusercontent.com/MominRaza/assets/main/Mayro%20Pro/3.png" width="400" alt="User Menu"/>
</p>

#### Ask A Question

<p float="left">
  <img src="https://raw.githubusercontent.com/MominRaza/assets/main/Mayro%20Pro/5.png" width="700" alt="Ask A Question"/>
  <img src="https://raw.githubusercontent.com/MominRaza/assets/main/Mayro%20Pro/6.png" width="700" alt="Ask A Question"/>
</p>

#### User

<p float="left">
  <img src="https://raw.githubusercontent.com/MominRaza/assets/main/Mayro%20Pro/10.png" width="600" alt="User"/>
  <img src="https://raw.githubusercontent.com/MominRaza/assets/main/Mayro%20Pro/9.png" width="600" alt="User"/>
</p>

#### Users

<p float="left">
  <img src="https://raw.githubusercontent.com/MominRaza/assets/main/Mayro%20Pro/11.png" width="400" alt="Users"/>
  <img src="https://raw.githubusercontent.com/MominRaza/assets/main/Mayro%20Pro/12.png" width="400" alt="Users"/>
</p>

#### Categories

<p float="left">
  <img src="https://raw.githubusercontent.com/MominRaza/assets/main/Mayro%20Pro/8.png" width="700" alt="Categories"/>
  <img src="https://raw.githubusercontent.com/MominRaza/assets/main/Mayro%20Pro/7.png" width="700" alt="Categories"/>
</p>

## Installation

1. [Install Question2Answer][]. This theme requires version 1.8 or later
1. [Download the latest version][latest release] of theme
1. Copy the `Mayro-Pro` folder to `qa-theme` directory of your q2a installation (eg. `qa-theme/Mayro-Pro`)
1. Visit `http://your-q2a-site.com/admin/general` , select the `Mayro Pro` for both the `Site theme` and `Theme for mobile`
1. For non English language only
   - In `qa-lang/your-lang/qa-lang-main.php` file find meta_order and make sure line is same as this
   ```php
   'meta_order' => "^what^when^where^who",
   ```
1. Congratulations, Mayro Pro theme is now up and running on your website :smile:

## Recommended Settings

### Admin/Users

- Avatar size on user profile page: 200 pixels
- Avatar size on top users page: 180 pixels
- Avatar size on questions: 50 pixels
- Avatar size on answers, comments, question lists, message lists: 40 pixels

### Admin/Lists

- Columns on Tags page, Users page: 1

## Manifest and Service Worker File Setup for PWA

### Step 1

- Edit `manifest.webmanifest` file as your requirements.
- Add these images in root directory of your website. (eg. `https://your-q2a-site.com/images/icons/192.png`)
  - `images/`
    - `icons/`
      - `icons-192.png`
      - `icons-192-maskable.png`
      - `icons-512.png`
      - `icons-512-maskable.png`
    - `shortcuts/` (find in `extra` folder)
      - `updates.png`
      - `ask.png`
    - `screenshots/` (optional)
      - `screenshot1.webp`
      - `screenshot2.webp`
      - `screenshot3.webp`
      - `screenshot4.webp`
      - `screenshot5.webp`
- For more info https://web.dev/add-manifest/

### Step 2

- Add `sw_offline.js` in your website root directory. (find in `extra` folder)
- Add `offline.html` in your website root directory and edit as your requirements. (find in `extra` folder)
- For more info https://web.dev/service-worker-mindset/

## Author

This theme is created with :heart: by [Momin Raza](https://mominraza.github.io)

## About Question2Answer

**Question2Answer** is a free and open source PHP and MySQL based platform for creating Question & Answer sites. For more information visit Q2A's official site at [question2answer.org](http://www.question2answer.org/)

[question2answer]: http://www.question2answer.org/
[question2answer ( q2a )]: http://www.question2answer.org/
[install question2answer]: http://www.question2answer.org/install.php
[mayro-pro]: https://github.com/MominRaza/MayroPro
[latest release]: https://github.com/MominRaza/MayroPro/releases/latest
