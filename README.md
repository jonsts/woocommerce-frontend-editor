# WooCommerce Frontend Product Editor

**Version:** 1.5  
**Author:** jonsts
**License:** GPL-2.0-or-later  
**Tags:** WooCommerce, Frontend, Product Editor, Variable Products  

---

## Table of Contents

- [Description](#description)
- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
- [Screenshots](#screenshots)
- [Changelog](#changelog)
- [Frequently Asked Questions](#frequently-asked-questions)
- [Contributing](#contributing)
- [License](#license)
- [Acknowledgements](#acknowledgements)
- [Contact](#contact)

---

## Description

The **WooCommerce Frontend Product Editor** plugin allows users with the appropriate permissions to edit the main information of variable products directly from the frontend of your WooCommerce store. This streamlines the editing process by eliminating the need to navigate to the WordPress admin dashboard.

---

## Features

- **Frontend Editing:** Edit product title, short description, and long description without accessing the backend.
- **Support for Variable Products:** Specifically designed to work with variable products, editing the parent product information.
- **Rich Text Editor:** Utilize the WordPress TinyMCE editor for rich text formatting.
- **User Permissions:** Only users with the `edit_products` capability (e.g., Administrators, Shop Managers) can edit products.
- **Modal Dialog Interface:** Clean and user-friendly modal dialog using the HTML `<dialog>` element.
- **Real-Time Updates:** Changes can be reflected immediately on the frontend.
- **Secure:** Includes nonce verification and permission checks to ensure secure editing.

---

## Installation

### Prerequisites

- WordPress version 5.0 or higher
- WooCommerce version 3.0 or higher
- PHP version 7.0 or higher

### Steps

1. **Download the Plugin:**

   - Clone the repository or [download the latest release](https://github.com/jonsts/wc-frontend-product-editor/releases).

2. **Upload the Plugin:**

   - Upload the `wc-frontend-product-editor` folder to the `/wp-content/plugins/` directory.

     **OR**

   - Compress the `wc-frontend-product-editor` folder into a `.zip` file.
   - In your WordPress admin dashboard, navigate to **Plugins > Add New > Upload Plugin** and upload the `.zip` file.

3. **Activate the Plugin:**

   - Go to **Plugins** in your WordPress admin dashboard.
   - Locate **WooCommerce Frontend Product Editor** and click **Activate**.

---

## Usage

### Editing a Variable Product

1. **Ensure Proper Permissions:**

   - You must be logged in as a user with the `edit_products` capability (e.g., Administrator or Shop Manager).

2. **Navigate to a Variable Product Page:**

   - On the frontend of your WooCommerce store, go to a product page of a variable product you wish to edit.

3. **Locate the Edit Icon:**

   - Next to the product title, you will see a pencil icon (🖉). This is the edit icon.

4. **Open the Edit Modal:**

   - Click on the pencil icon to open the edit modal dialog.

5. **Edit Product Information:**

   - **Product Title:** Modify the product's title as needed.
   - **Short Description:** Use the rich text editor to edit the short description.
   - **Description:** Use the rich text editor to edit the long description.

6. **Save Changes:**

   - Click the **Save Changes** button.
   - A spinner will appear, and the button will be disabled during the save process.
   - Upon success, a confirmation message will display.

7. **Close the Modal:**

   - The modal will close automatically after a short delay, or you can click the **Close** button.

8. **Verify Updates:**

   - Refresh the product page to see the updated information.

## Changelog

### Version 1.5

- **Added:** Rich text editor for product descriptions using `wp_editor()`.
- **Fixed:** Spinner visibility and button disable state during save process.
- **Improved:** Placement of the edit icon next to the product title.
- **Updated:** Modal dialog to use the HTML `<dialog>` element with polyfill for compatibility.
- **Enhanced:** Security checks and data sanitization.

---

## Frequently Asked Questions

### 1. **Who can use this plugin to edit products?**

Only users with the `edit_products` capability, such as Administrators and Shop Managers, can edit products using this plugin.

### 2. **Does this plugin support simple products?**

Currently, the plugin is designed to work with variable products, allowing you to edit the parent product information. Support for simple products may be added in future updates.

### 3. **Why am I not seeing the edit icon on the product page?**

Ensure that you are logged in with a user account that has the necessary permissions. Also, verify that the product is a variable product.

### 4. **The rich text editor is not appearing; what should I do?**

- Clear your browser cache and any caching plugins.
- Ensure that no JavaScript errors are present in your browser's console.
- Verify that the `wp_enqueue_editor()` function is properly enqueued in the plugin code.

### 5. **Can I customize the toolbar of the rich text editor?**

Yes, you can customize the TinyMCE toolbar by modifying the `tinymce` settings in the `wp_editor()` function within the plugin code.

---

## Contributing

Contributions are welcome! Please follow these steps:

1. **Fork the Repository:**

   - Click on the **Fork** button at the top-right corner of this page.

2. **Clone Your Fork:**

   ```bash
   git clone https://github.com/jonsts/wc-frontend-product-editor.git
