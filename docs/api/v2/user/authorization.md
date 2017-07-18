# Create user authentication token and refresh token

- [Create user authentication token](#create-user-authentication-token)
- [Refresh token](#refresh-token)

## Create user authentication token

```
POST /login
```

### Input

| Name | Type | Description |
|:----:|:----:|----|
| login | String | **Required**,User auth field. Can use `name` / `email` / `phone` |
| password | String | **Required**, User password. |

#### Response

```
Status: 201 Created
```
```json
{
    "token": "Kyle5NWouF3cbDUsAeC1zdzQ1xTvBi62UZtmFG2d16M86dyztNyRTPjx1ZydD0rH",
    "refresh_token": "8JucelIqUHcfQfRpujXhrg8rzG7zOOWREPALh2kh5EClpxhyfzFFXdnHwfgtJlqr",
    "user_id": 1,
    "expires": 0,
    "state": 1,
    "updated_at": "2017-07-18 04:51:25",
    "created_at": "2017-07-18 04:51:25",
    "id": 11
}
```

## Refresh token
