# WordPress 404 动画错误页面插件

## 📁 插件文件结构
```
argon-404-page/
├── argon-404-page.php      # 插件主文件
├── style.css               # 样式（编译自 style.scss）
└── README.md
```

## 📦 安装方式

| 方式 | 操作 |
|:---: | :--- |
| 手动 | 将 `argon-404-page` 文件夹上传至 `/wp-content/plugins/` ，后台启用 |
| ZIP | 打包为 ZIP 后在后台「插件 → 安装插件 → 上传」 |

## ✨ 功能清单（对标 Argon 主题规范）
| 特性 | 状态 | 说明 |
|:--- | :---: | :--- |
| 👀 眼睛四处张望动画（eye） | ✅ | 2.5s 无限循环，5 种姿态 |
| 🫨 头部晃动动画（shvr） | ✅ | 0.2s 无限循环 |
| 💬 文字滚动渐显（text-show） | ✅ | 2s steps(3) 循环 |
| 📝 404 大字显示 | ✅ | 80px 加粗 |
| 🔙 返回上一页按钮 | ✅ | `wp_get_referer()` + JS 兜底 |
| 🏠 回到首页按钮 | ✅ | 链接至 `home_url()` |
| 🔍 搜索框 | ✅ | 指向 WordPress 搜索 |
| 🌙 夜间模式适配 | ✅ | 继承 `body.night` class |
| 📱 响应式布局 | ✅ | flex-wrap 自适应 |
| 🎨 配色 #1EA7AB | 与 Argon Theme 保持一致 |

## 🔄 与原文件的对应关系

| 原文件 | 插件中的实现 |
|:--- | :--- |
| `index.html` 的 `<head>` 动画 | `.a404-head` + `.a404-head::after` |
| `style.css` 的 `meta` 耳朵 | `.a404-meta` × 3 |
| `style.css` 的 `body::before/after` | `.a404-body::before/after` |
| `style.css` 的 Compass Mixin | 已转为原生 CSS（WordPress 兼容） |
| Argon 主题的按钮风格 | `.a404-btn-primary` / `.a404-btn-secondary` |
