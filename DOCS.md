# Evaluation Criteria

## Features
- Title
  - The title of popup box will appear
- Content
  - Anything from the content post will appear in content body of the popup box
  - You can insert image, set the alignment, link, etc
- Trigger
  - When the popup will appear (in ms)
- Targeting
  - Select where the popup will appear
    - Homepage
    - Search Result
    - 404 Error Page
    - All Posts
    - Post: Selected (Input the Post ID)
    - All Pages
    - Pages: Selected (Input the Page ID)
- Session
  - Who the popup will appear for
    - All Users
    - Logged In Users
    - Non Users Login
- Display
  - The layout position of popup
    - Left Bottom
    - Left Top
    - Center
    - Right Bottom
    - Right Top
- Size
  - The design size of the popup
    - xsmall
    - small
    - medium
    - large
    - xlarge

## Plugin Size
<img src="https://img.shields.io/github/languages/code-size/isonnymichael/simplepopup-framework" alt="Code Size">

## OOP
- Utilize a namespace distinct from `Dot`. ✅
  - Using aspri I refactor `Dot` into `SimplePopup`
- Employ the Singleton Pattern and provide comments for clear identification. ✅
  - Employ the Single Pattern on Plugin class to manage and utilize framework
- Implement the Prototype Pattern with accompanying comments for easy recognition. ✅
  - Using clone to action and filter from framework

## Wordpress
- Employ WordPress CPT (Custom Post Types) and Custom Fields without external plugin assistance, coding them yourself. ✅
  - Utilize Dot framework to make custom post types not using external plugin
  - The file is on the `src/Controller/Backend/Backend.php`
- Define the Post Type with fields for title, description, and page. The 'page' should specify where the Pop Up should be displayed. ✅
  - Utilize Dot framework to make meta data
    - Controller `src/Controller/Metabox`
    - Helper `src/Helper/SimplePopup` && `src/Helper/SimplePopupMetabox`
    - Model `src/Model/SimplePopup.php`
    - View `src/View/Backend` && `src/View/Frontend`

## Design & Implementation
- Utilize SASS for designing the plugin. ✅
  - Utilize build-in Dot Framework to design CSS for the plugin `assets/css/_external.scss` build with grunt
- Employ Typescript when implementing the Pop Up, without reliance on external libraries. ✅
  - Without external library `assets/ts` coding myself to make feature popup build with grunt

## Integration
- Utilize WordPress REST API to access the Pop Up via `/wp-json/artistudio/v1/popup`. ✅
  - Utilize Dot Framework to make action register REST API on `/wp-json/artistudio/v1/popup` with GET method
  - Api `src/Api/BackendAPI.php`
- Ensure that API endpoints are accessible exclusively to logged-in users. ✅
  - Make function `restrict_api_access` to restrict access
- Prohibit non-logged-in users from accessing these endpoints. ✅
  - Return `WP_Error` if non-logged-in users trying to access endpoint

