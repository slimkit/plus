# Create user authentication token and refresh token

- [Create user authentication token](#create-user-authentication-token)
- [Refresh token](#refresh-token)

## Create user authentication token

```
POST /tokens
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
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6Ly9wbHVzLmlvL2FwaS92Mi90b2tlbnMiLCJpYXQiOjE1MDAzNjU5MzQsImV4cCI6MTUwMTU3NTUzNCwibmJmIjoxNTAwMzY1OTM0LCJqdGkiOiJ1aXlvdTQwNnJsdU9pa3l3In0.OTM4mbH3QW7busunRsFUsheE5vysuIfrBrwjWnd0J6k",
    "user": {
        "id": 1,
        "name": "创始人",
        "bio": "我是大管理员",
        "sex": 0,
        "location": "成都市 四川省 中国",
        "created_at": "2017-06-02 08:43:54",
        "updated_at": "2017-07-06 07:04:06",
        "avatar": "http://plus.io/api/v2/users/1/avatar",
        "extra": {
            "user_id": 1,
            "likes_count": 0,
            "comments_count": 0,
            "followers_count": 0,
            "followings_count": 1,
            "updated_at": "2017-07-16 09:44:25",
            "feeds_count": 0
        }
    },
    "ttl": 20160,
    "refresh_ttl": 40320
}
```

| Field name | Description |
|:----:|----|
| token | Authorization code of JSON web Token. |
| user | User data. |
| ttl | Authorization code expiration interval |
| refresh_ttl | Use the authorization code to refresh the interval of the authorization code. |

> `TTL` time in **minutes**

## Refresh token

```
PATCH /tokens/:token
```

#### Response

```
Status: 201 Created
```
```json
{
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6Ly9wbHVzLmlvL2FwaS92Mi90b2tlbnMvZXlKMGVYQWlPaUpLVjFRaUxDSmhiR2NpT2lKSVV6STFOaUo5LmV5SnpkV0lpT2pFc0ltbHpjeUk2SW1oMGRIQTZMeTl3YkhWekxtbHZMMkZ3YVM5Mk1pOTBiMnRsYm5NaUxDSnBZWFFpT2pFMU1EQXpOalUxTVRjc0ltVjRjQ0k2TVRVd01UVTNOVEV4Tnl3aWJtSm1Jam94TlRBd016WTFOVEUzTENKcWRHa2lPaUpLT1RKVlJsRlRaVm96UkRGUFVsaFRJbjAuejNkNTBXZm5lSUIyTk45N2FSWW9lSFAwUjhyN1l0STJSVmxRUEVFWElaSSIsImlhdCI6MTUwMDM2NTUxNywiZXhwIjoxNTAxNTc1MTI4LCJuYmYiOjE1MDAzNjU1MjgsImp0aSI6IkV0S3VXUWd2VHlkMnpQSXcifQ.MJt3fz0hgH7BJNa1oC-9H3BZa3vIxS2oHu5OG9g39O8",
    "ttl": 20160,
    "refresh_ttl": 40320
}
```
