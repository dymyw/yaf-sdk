## yaf-sdk

Yaf 扩展实践

### 封装

- 数据返回
    - json
- 数据操作
    - catfan/medoo
- 参数
    - 获取
    - 验证 wixel/gump
- 错误
    - 异常
    - 日志
- 缓存
    - Redis
        - 规范 Redis Key 定义

### 优化

Action 获取参数

    每次都需要先 getController() 才能获取

封装一个参数验证包

    wixel/gump 中文包是自定义的
    wixel/gump 的 contains 有问题，自定义了验证规则 in
