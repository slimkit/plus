# User sign up

```
POST /users
```

### Input

| Name | Type | Description |
|:----:|:----:|----|
| name | String | **Required**, User register name. |
| phone | String | **Required without `email`**, User China phone bumber. |
| email | String | **Required withput `phone`**, User E-Mail. |
| password | String | **Required**, User password. |
| verifiable_type | Enum: mail, sms | **Required**, Notification serve verification type. |
| verifiable_code | Strint\|Number | **Required**, Verification code. |

#### Response

```
Status: 201 Created
```
```json
{
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6Ly9wbHVzLmlvL2FwaS92Mi90b2tlbnMiLCJpYXQiOjE1MDAzNjU5MzQsImV4cCI6MTUwMTU3NTUzNCwibmJmIjoxNTAwMzY1OTM0LCJqdGkiOiJ1aXlvdTQwNnJsdU9pa3l3In0.OTM4mbH3QW7busunRsFUsheE5vysuIfrBrwjWnd0J6k",
    "ttl": 20160,
    "refresh_ttl": 40320
}
```
