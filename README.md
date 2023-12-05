## ASE 230 Midterm Project, "CreateHub"
By Bryce Bien and Justin Walter

video: 

## Requirements: 
Instructions ---
Your starting point is the work you submitted for the Mid-term, which should incorporate the feedback you already received for that assignment.


Design and development ---

~~1. Finalize and implement the complete DATA LAYER of your application using MySQL.~~
~~2. Implement an authentication module that enables signing the user up, in, and out, recognizing their role (regular user or admin)~~
3. Implement the complete LOGIC LAYER of the Content Management System, which should be organized into
    1. Entities of your application:
        1. Each entity type should be accessible via a URL that looks like "yourwebsite/entityname".
        2. Each entity type should involve index, detail, create, edit, and delete pages. These pages should support roles and permissions as shown below. 
    2. Roles and permissions: based on the way your application works, for each entity, you should implement:
        1. public "content": this involves content that a visitor can see even if they are not signed in. By "content", I mean an entire set of entities (e.g., the products of an e-commerce website are visible to everybody), a group of pages (e.g., the index and detail pages could be visible to everybody, but the create page should be visible to registered users only), and elements of a page (e.g., the "modify" or "edit" buttons inside a page should only be visible to the author of the entity and the admin, even if the rest of the page is visible to everybody).
        2. members "area": this involves content that a user can see only after logging in. For example, this can be a series of pages that only users can see (e.g., "your orders" in an e-commerce website) or specific interface components that are visible to the owners of the entity only (e.g., the button for modifying or deleting a post in a social media website). 
        3. admin "area": for each entity, this involves a separate area that enables the admin of the website to index, detail, create, edit, and delete everything. 
4. Finalize the PRESENTATION LAYER of the Content Management System by applying the chosen theme to EVERY page (including the admin area)
5. The team leader will create a new repository (public) on GitHub, invite the other members as collaborators, and create a file called "README.md" where they will list the collaborators.
6. Each member will push their work on GitHub on their branch.
7. The team leader will integrate (merge) the work of each team member into the main branch and push the changes.


Documentation

Prepare a 3-slide presentation in which you describe:
Slide 1: The project idea (what does it do?)
Slide 2: The ER diagram
Slide 3: The team members and their contribution


Presentation

Record a video (e.g., screencast, max 10 minutes) in which you present
1. your slides (2 minutes). The emphasis here is to provide an overview of the idea, ER, and contribution of each team member
2. a demo of your project and its key features (8 minutes), highlighting the aspects you consider worth mentioning and commenting on the source code where needed. You can have each member talk about what they implemented, or you can have one member record the video.

The goal of the video is to demonstrate how the features work and provide a QUICK overview of the code
At the end of the video, discuss any changes you've made to the original idea.


Submission guidelines ---

Submit:

the link to the GitHub repository. The repository should contain:
Your project files (the website)
A "doc" folder that includes:
the SQL dump of the database, which should have data in it
a Readme.md file with:
a set of user credentials for me to test the website
a set of admin credentials for me to test the website
a link to the video published on YouTube as "unlisted".



## Other stuff:
# [Start Bootstrap - Creative](https://startbootstrap.com/theme/creative/)

[Creative](https://startbootstrap.com/theme/creative/) is a one page, creative website theme built with [Bootstrap](https://getbootstrap.com/) created by [Start Bootstrap](https://startbootstrap.com/).

## Preview

[![Creative Preview](https://assets.startbootstrap.com/img/screenshots/themes/creative.png)](https://startbootstrap.github.io/startbootstrap-creative/)

**[View Live Preview](https://startbootstrap.github.io/startbootstrap-creative/)**

## Status

[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/StartBootstrap/startbootstrap-creative/master/LICENSE)
[![npm version](https://img.shields.io/npm/v/startbootstrap-creative.svg)](https://www.npmjs.com/package/startbootstrap-creative)

## Download and Installation

To begin using this template, choose one of the following options to get started:

- [Download the latest release on Start Bootstrap](https://startbootstrap.com/theme/creative/)
- Install using npm: `npm i startbootstrap-creative`
- Clone the repo: `git clone https://github.com/StartBootstrap/startbootstrap-creative.git`
- [Fork, Clone, or Download on GitHub](https://github.com/StartBootstrap/startbootstrap-creative)

## Usage

### Basic Usage

After downloading, simply edit the HTML and CSS files included with `dist` directory. These are the only files you need to worry about, you can ignore everything else! To preview the changes you make to the code, you can open the `index.html` file in your web browser.

### Advanced Usage

Clone the source files of the theme and navigate into the theme's root directory. Run `npm install` and then run `npm start` which will open up a preview of the template in your default browser, watch for changes to core template files, and live reload the browser when changes are saved. You can view the `package.json` file to see which scripts are included.

#### npm Scripts

- `npm run build` builds the project - this builds assets, HTML, JS, and CSS into `dist`
- `npm run build:assets` copies the files in the `src/assets/` directory into `dist`
- `npm run build:pug` compiles the Pug located in the `src/pug/` directory into `dist`
- `npm run build:scripts` brings the `src/js/scripts.js` file into `dist`
- `npm run build:scss` compiles the SCSS files located in the `src/scss/` directory into `dist`
- `npm run clean` deletes the `dist` directory to prepare for rebuilding the project
- `npm run start:debug` runs the project in debug mode
- `npm start` or `npm run start` runs the project, launches a live preview in your default browser, and watches for changes made to files in `src`

You must have npm installed in order to use this build environment.

### Contact Form

The contact form available with this theme is prebuilt to use [SB Forms](https://startbootstrap.com/solution/contact-forms).
SB Forms is a simple form solution for adding functional forms to your theme. Since this theme is prebuilt using our
SB Forms markup, all you need to do is sign up for [SB Forms on Start Bootstrap](https://startbootstrap.com/solution/contact-forms).

After signing up you will need to set the domain name your form will be used on, and you will then see your
access key. Copy this and paste it into the `data-sb-form-api-token='API_TOKEN'` data attribute in place of
`API_TOKEN`. That's it! Your forms will be up and running!

If you aren't using SB Forms, simply delete the custom data attributes from the form, and remove the link above the
closing `</body>` tag to SB Forms.

## Bugs and Issues

Have a bug or an issue with this template? [Open a new issue](https://github.com/StartBootstrap/startbootstrap-creative/issues) here on GitHub or leave a comment on the [theme overview page at Start Bootstrap](https://startbootstrap.com/theme/creative/).

## About

Start Bootstrap is an open source library of free Bootstrap themes and templates. All of the free themes and templates on Start Bootstrap are released under the MIT license, which means you can use them for any purpose, even for commercial projects.

- <https://startbootstrap.com>
- <https://twitter.com/SBootstrap>

Start Bootstrap was created by and is maintained by **[David Miller](https://davidmiller.io/)**.

- <https://davidmiller.io>
- <https://twitter.com/davidmillerhere>
- <https://github.com/davidtmiller>

Start Bootstrap is based on the [Bootstrap](https://getbootstrap.com/) framework created by [Mark Otto](https://twitter.com/mdo) and [Jacob Thorton](https://twitter.com/fat).

## Copyright and License

Copyright 2013-2023 Start Bootstrap LLC. Code released under the [MIT](https://github.com/StartBootstrap/startbootstrap-creative/blob/master/LICENSE) license.
