#API

The webapplication contains also a small REST API which let's you do basic operation with resources. 

##General

The api can be found at `/api`. All endpoints which are outlined here need to be appended to `/api`.

### Authentification

For authentification a custom `HEADER` is used. The name of the header is `X-API-KEY`. And the value of it should be an API key of a user. The API key can be found in the settings of a user. A valid content type will then be like: 

`X-API-KEY: 5e0190f72907e173c6c0f48453d069e8`  

###Content-Type

Make sure to set the content type to `application/json` for all requests that accept a body. Only requests with `application/json` as Content-type will be handled.

##Public Endpoints

These endpoints do not need authentification. 

### `GET data/stores` 

Get all different stores that were once entered in the application. This endpoint is also used internally for autocompletion in the purchase form. 

**Parameters:** none

##Endpoints

These endpoints need authentification with the `X-API-KEY` header. See the section about authentification above for more information. 

---

###`GET accounts`

Returns all accounts a user has access to.

**Parameters:** none

**Request:** `GET /api/accounts`

**Response:**

	HTTP Status: 200 
	
	{ "account": [
        {
            "id": 1,
           	"name": "Ausgaben Privat"
        }
    ]}
    
    
---

###`POST accounts/[id]/purchases`

Add a new purchase to an account. 

You have the possibility to specify the `verified` key. It's optional and defaults to true. 
If the purchase is added by a program it may be a good idea to let the user verify a purchase. This means if `verified` is set to false, it will appear on the users main page where he has the possibilty to verify or edit the purchase.

**Parameters:** 

`id`: An id of an account. 

**Request:** `POST /api/accounts/1/purchases`

	{
  		"store" 		: "Denner",
  		"description" 	: "A short description",
  		"amount"		: "12.50",
  		"date"			: "2015.08.28"
  		"verified"		: false
	}

**Response:**

	HTTP Status: 201
	

---

###`POST accounts/[accountid]/purchases/[purchaseid]/receipt`

Add a receipt (image) to a purchase.

**Parameters:** 

`accountid`: An id of an account. 
`purchaseid`: An id of a purchase.

**Request:** `POST /api/accounts/1/purchases/43/receipt`

Send the image as `multipart/form-data` under the key `receipt`

**Response:**

	HTTP Status: 201