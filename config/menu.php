<?php

return [
    [
        "path" => "/member",
        "component" => "layout",
        "name" => "Member",
        "meta" => [
            "title" => "成员管理",
            "icon" => "el-icon-user",
            "uri" => "/member"
        ],
        "children" => [
            [
                "path" => "user",
                "name" => "User",
                "component" => "user",
                "meta" => [
                    "title" => "用户",
                    "uri" => "/member/user"
                ]
            ],
            [
                "path" => "author",
                "name" => "Author",
                "component" => "author",
                "meta" => [
                    "title" => "作者",
                    "uri" => "/member/author"
                ]
            ],
            [
                "path" => "admin",
                "name" => "Admin",
                "component" => "admin",
                "meta" => [
                    "title" => "管理员",
                    "uri" => "/member/admin"
                ]
            ],
        ]
    ],
    [
        "path" => "/article",
        "component" => "layout",
        "name" => "Article",
        "meta" => [
            "title" => "文章管理",
            "icon" => "el-icon-postcard",
            "uri" => "/article"
        ],
        "children" => [
            [
                "path" => "list",
                "name" => "List",
                "component" => "article",
                "meta" => [
                    "title" => "文章列表",
                    "uri" => "/article/list"
                ]
            ],
            [
                "path" => "category",
                "name" => "Category",
                "component" => "category",
                "meta" => [
                    "title" => "文章分类",
                    "uri" => "/article/category"
                ]
            ],
        ]
    ],
    [
        "path" => "/test",
        "component" => "layout",
        "name" => "Test",
        "meta" => [
            "title" => "测试",
            "icon" => "el-icon-question",
            "uri" => "/test"
        ],
        "children" => [
            [
                "path" => "test1",
                "name" => "Test1",
                "component" => "test1",
                "meta" => [
                    "title" => "测试1",
                    "icon" => "el-icon-question",
                    "uri" => "/test/test1"
                ],
                "children" => [
                    [
                        "path" => "test1-1",
                        "name" => "Test1-1",
                        "component" => "test1_1",
                        "meta" => [
                            "title" => "测试1-1",
                            "icon" => "el-icon-question",
                            "uri" => "/test/test1/test1-1"
                        ],
                        "children" => [
                            [
                                "path" => "test1-1-1",
                                "name" => "Test1-1-1",
                                "component" => "test1_1_1",
                                "meta" => [
                                    "title" => "测试1-1-1",
                                    "uri" => "/test/test1/test1-1/test1-1-1"
                                ]
                            ],
                            [
                                "path" => "test1-1-2",
                                "name" => "Test1-1-2",
                                "component" => "test1_1_2",
                                "meta" => [
                                    "title" => "测试1-1-2",
                                    "uri" => "/test/test1/test1-1/test1-1-2"
                                ]
                            ],
                            [
                                "path" => "test1-1-3",
                                "name" => "Test1-1-3",
                                "component" => "test1_1_3",
                                "meta" => [
                                    "title" => "测试1-1-3",
                                    "uri" => "/test/test1/test1-1/test1-1-3"
                                ]
                            ],
                        ]
                    ],
                    [
                        "path" => "test1-2",
                        "name" => "Test1-2",
                        "meta" => [
                            "title" => "测试1-2",
                            "icon" => "el-icon-question",
                            "uri" => "/test/test1/test1-2"
                        ],
                        "component" => "test1_2",
                    ]
                ]
            ],
            [
                "path" => "test2",
                "name" => "Test2",
                "component" => "test2",
                "meta" => [
                    "title" => "测试2",
                    "icon" => "el-icon-question",
                    "uri" => "/test/test2"
                ],
                "children" => [
                    [
                        "path" => "test2-1",
                        "name" => "Test2-1",
                        "meta" => [
                            "title" => "测试2-1",
                            "icon" => "el-icon-question",
                            "uri" => "/test/test2/test2-1"
                        ],
                        "component" => "test2_1",
                    ],
                    [
                        "path" => "test2-2",
                        "name" => "Test2-2",
                        "meta" => [
                            "title" => "测试2-2",
                            "icon" => "el-icon-question",
                            "uri" => "/test/test2/test2-2"
                        ],
                        "component" => "test2_2",
                    ],
                ]
            ]
        ]
    ],
    [
        "path" => "/default",
        "name" => "Default",
        "component" => "layout",
        "meta" => [
            "icon" => "el-icon-help",
            "uri" => "/default"
        ],
        "children" => [
            [
                "path" => "index",
                "name" => "Index",
                "component" => "default",
                "meta" => [
                    "title" => "默认路由",
                    "uri" => "/default/index"
                ]
            ]
        ]
    ]
];
