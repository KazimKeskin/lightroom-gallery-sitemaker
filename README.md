# Lightroom Gallery Sitemaker
A PHP script that helps automate the production of Adobe Lightroom web gallery based websites.

Customises all of your gallery pages at once so that adjustments to your website can be replicated across every page, making updating your galleries, potentially, much easier.

## Components
- info panel displaying exif data
- full-size image download button
- comment form
- galleries navigation menu and mobile menu
- (Planned) tag powered gallery system

## Background
This is a script that I wrote in order to customise Lightroom gallery exports before uploading them to my website. 

It uses DOM manipulation to inject HTML and some JavaScript into the files generated by Lightroom and adds CSS and JavaScript files for additional features that I desired.

I have untweaked the script's specificity to my website and thus I would expect some customisation to be wanted, either before or after running the script, however the script will work to simply add features to the standard Lightroom web gallery export.

This works with Adobe Lightroom Classic 9.0, with the Grid Gallery Templates and I have not tested it with other templates, nor do I know whether the required features are available in other versions of Lightroom.

## Usage
- Prerequisites
  - Currently you will need PHP installed and callable (either through PATH variable or by running the exe along with the script).
-	Export galleries from Lightroom using one of the "Grid Gallery" templates and with "Show Header" off. (If you are customising the script, then feel free to try with different templates but the HTML file produced from the export will have a different structure and you will need to account for that.)
-	Place them into a folder such that the folder structure is 'galleries/[gallery-title]'.
-	For each gallery, export full-sized images with the metadata desired into a folder 'galleries/[gallery-title]/images/full'. (this is required both for the full-sized image download and for the info panel)
-	Edit the variables in 'settings.php'
    - Change $dir to the full path to your galleries folder e.g. "C:/website/galleries" (making sure to include the quotation marks.)
    - Turn optional features on and off by changing between 1 and 0.
-	Then run the script.
    -	In the command line, navigate to the directory of the script e.g.
      `cd /d "C:\downloads/lightroom-gallery-sitemaker"`
    - Execute
      - if you have PHP as a PATH variable `php build.php`
      - if not, you must replace "php" with the location of your php.exe file `C:/php/php.exe' build.php`
     
## Customisation
  The script is written as a sort of template with enough abstractedness that makes it trivial to edit and add features.
  
  The $stylesheets variable and the $scripts variable can have further added to them, allowing additional stylesheets and scripts to be added, and the function will inject them all into the HTML and add them into the assets folder, although you may like to customise their location.

  With the createElement function in 'build.php' it becomes possible to build HTML structure out of elements and then using DOM manipulation inject the HTML where necessary. Following the structure as templated by various code blocks within 'updateindexfile.php', complex structured code can be proceduraly generated and placed into an HTML file or any other text file like JavaScript or CSS. As it is one procedure, code blocks can simply be added into 'updateindexfile.php'.
