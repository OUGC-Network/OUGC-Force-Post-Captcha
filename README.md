<p align="center">
    <a href="" rel="noopener">
        <img width="700" height="400" src="https://github.com/OUGC-Network/OUGC-Force-Post-Captcha/assets/1786584/02ce7455-3680-4df3-9dc6-0285c465b619" alt="Project logo">
    </a>
</p>

<h3 align="center">OUGC Force Post Captcha</h3>

<div align="center">

[![Status](https://img.shields.io/badge/status-active-success.svg)]()
[![GitHub Issues](https://img.shields.io/github/issues/OUGC-Network/OUGC-Force-Post-Captcha.svg)](./issues)
[![GitHub Pull Requests](https://img.shields.io/github/issues-pr/OUGC-Network/OUGC-Force-Post-Captcha.svg)](./pulls)
[![License](https://img.shields.io/badge/license-GPL-blue)](/LICENSE)

</div>

---

## 📜 Table of Contents <a name = "table_of_contents"></a>

- [About](#about)
- [Getting Started](#getting_started)
    - [Dependencies](#dependencies)
    - [File Structure](#file_structure)
    - [Install](#install)
    - [Update](#update)
    - [Template Modifications](#template_modifications)
- [Settings](#settings)
- [Usage](#usage)
    - [Example Configurations](#usage_examples)
- [Built Using](#built_using)
- [Authors](#authors)
- [Acknowledgments](#acknowledgement)
- [Support & Feedback](#support)

## 🚀 About <a name = "about"></a>

Hey there! Ever had to deal with pesky spam on your MyBB forum? Say no more with "Force Post Captcha"! This awesome
plugin lets you easily set up the captcha verification for specific groups when they're posting in certain forums or
using the contact form. Keep your forum safe and spam-free with this plugin – it's the easy way to keep the bots out!

[Go up to Table of Contents](#table_of_contents)

## 📍 Getting Started <a name = "getting_started"></a>

The following information will assist you into getting a copy of this plugin up and running on your forum.

### Dependencies <a name = "dependencies"></a>

A setup that meets the following requirements is necessary to use this plugin.

- [MyBB](https://mybb.com/) >= 1.8
- PHP >= 7
- [MyBB-PluginLibrary](https://github.com/frostschutz/MyBB-PluginLibrary) >= 13

### File structure <a name = "file_structure"></a>

  ```
   .
   ├── inc
   │ ├── languages
   │ │ ├── english
   │ │ │ ├── admin
   │ │ │ │ ├── ougc_force_post_captcha.lang.php
   │ ├── plugins
   │ │ ├── ougc_force_post_captcha
   │ │ │ │ ├── admin.php
   │ │ │ │ ├── core.php
   │ │ │ │ ├── forum_hooks.php
   │ │ ├── ougc_force_post_captcha.php
   ```

### Installing <a name = "install"></a>

Follow the next steps in order to install a copy of this plugin on your forum.

1. Download the latest package from the [MyBB Extend](https://community.mybb.com/mods.php?action=view&pid=1403) site or
   from the [repository releases](https://github.com/OUGC-Network/OUGC-Force-Post-Captcha/releases/latest).
2. Upload the contents of the _Upload_ folder to your MyBB root directory.
3. Browse to _Configuration » Plugins_ and install this plugin by clicking _Install & Activate_.

### Updating <a name = "update"></a>

Follow the next steps in order to update your copy of this plugin.

1. Browse to _Configuration » Plugins_ and deactivate this plugin by clicking _Deactivate_.
2. Follow step 1 and 2 from the [Install](#install) section.
3. Browse to _Configuration » Plugins_ and activate this plugin by clicking _Activate_.

### Template Modifications <a name = "template_modifications"></a>

To display the page link it is required that you edit the following template for each of your themes.

1. Open the `showthread_quickreply` template for editing.
2. Add `<!--OUGC_FORCE_POST_CAPTCHA-->` before `{$captcha}`.

[Go up to Table of Contents](#table_of_contents)

## 🛠 Settings <a name = "settings"></a>

Below you can find a description of the plugin settings.

### Global Settings

- **Affected Groups** `select`
    - _Select which groups are forced to fill a captcha when posting._
- **Affected Forums** `select`
    - _Select in which forums to force the selected groups to fill a captcha when posting._
- **First Post Only** `yesNo`
    - _Require a captcha only when posting threads (first post)._
- **Contact Page** `yesNo`
    - _Require a captcha when using the contact page._

[Go up to Table of Contents](#table_of_contents)

## 📖 Usage <a name="usage"></a>

This plugin has no additional configurations; after activating make sure to modify the global settings in order to get
this plugin working.

[Go up to Table of Contents](#table_of_contents)

## ⛏ Built Using <a name = "built_using"></a>

- [MyBB](https://mybb.com/) - Web Framework
- [MyBB PluginLibrary](https://github.com/frostschutz/MyBB-PluginLibrary) - A collection of useful functions for MyBB
- [PHP](https://www.php.net/) - Server Environment

[Go up to Table of Contents](#table_of_contents)

## ✍️ Authors <a name = "authors"></a>

- [@Omar G](https://github.com/Sama34) - Idea & Initial work

[Go up to Table of Contents](#table_of_contents)

## 🎉 Acknowledgements <a name = "acknowledgement"></a>

- [The Documentation Compendium](https://github.com/kylelobo/The-Documentation-Compendium)

[Go up to Table of Contents](#table_of_contents)

## 🎈 Support & Feedback <a name="support"></a>

This is free development and any contribution is welcome. Get support or leave feedback at the
official [MyBB Community](https://community.mybb.com/thread-229893.html).

Thanks for downloading and using our plugins!

[Go up to Table of Contents](#table_of_contents)