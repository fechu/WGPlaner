# URL Naming Guide

This documents describes how URLs should be designed.

## Webapp

### Examples

The URLs are designed in a **"REST"** style. This means that the main part of every URL are resources. And all other parts describe what to do with these resources.

Example: `/accounts/1`

This will show the account with `id = 1`. This URL can be extended with further parts like this:

`/accounts/1/purchases`

It then will list all purchases of account 1. 

But the URL does not have to contain only resources. It can also contain actions.

`accounts/1/add-purchase`

This will present a form to the user where he can add a purchase. 

### Resource naming

Resources names are always chosen in **plural** (by definition). This will result in a consistent look and feel. 

## API

For a complete documentation of the API, have a look at the `api.md` document. The URLs are designed the same way for the API and the webapplication.

