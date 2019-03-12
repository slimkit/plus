---
title: 问答
---

## 删除问题

```
DELETE /api/v2/qa/questions/{id}
```

> 仅拥有 `[Q&A] Manage Questions` 或者是问题发布者本人才有权限删除

响应：

```
Status: 204 No Content
```

## 删除回答

```
DELETE /api/v2/qa/answers/{id}
```

> 仅拥有 `[Q&A] Manage Answers` 或者是回答发布者本人才有权限删除

响应：

```
Status: 204 No Content
```
