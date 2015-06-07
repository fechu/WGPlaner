# Purchase Templates

This document describes how the purchase templates are implemented internally. 

## Entity Structure

Have a look on the following table to see how the different entites relate to each other. Find a table below that explains what each entities role is.

                +----------------------+                    
                |   PurchaseTemplate   |                    
                +----------------------+                    
                 1|                 1|                      
            +-----+                  +---+                  
            |                            |                  
           n|                           n|                  
    +-----------------------+ 1    n +-------------------------+
    | PurchaseTemplateStore +--------+ PurchaseTemplateContent |
    +-----------------------+        +-------------------------+

- **PurchaseTemplate**: The main entity which represents a template. 
- **PurchaseTemplateStore**: Represents a store in the template. A `PurchaseTemplate` can contain multiple `PurchaseTemplateStore`.
- **PurchaseTemplateContent**: The actual content (Description, Price) of a template. 

## Expected Behaviour

The UI should have approximately the following functionality. 

All `PurchaseTemplate` should be listed. When a `PurchaseTemplate` is selected all `PurchaseTemplateStore` are presented. When a user selects a `PurchaseTemplateStore` it's `PurchaseTemplateContent`s are presented and the user can select one to fill in the form.
If a `PurchaseTemplate`has no associated `PurchaseTemplateStore` all `PurchaseTemplateContent` are presented and the `defaultStore` of the `PurchaseTemplate` is used. 