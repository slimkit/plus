# Send verification code

> Send a verification code to support `email` and `phone` delivery.

Non - owner user:

```
POST /verifycodes/register
```

Owner user:

```
POST /verifycodes
```

### Input

| Name | Type | Description |
|:----:|:----:|----|
| phone | String | **Required without `email`**, Send the verification code in `sms` mode. |
| email | String | **Required without `phone`**, Send the verification code in `mail` mode. |

#### Response

```
Status: 202 Accepted
```
```json
{
    "message": [
        "获取成功"
    ]
}
```