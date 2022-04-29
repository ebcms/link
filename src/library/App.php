<?php

declare(strict_types=1);

namespace App\Ebcms\Link;

use App\Ebcms\Link\Http\Jump;
use DiggPHP\Framework\AppInterface;
use DiggPHP\Framework\Framework;
use PDO;

class App implements AppInterface
{

    public static function onDispatch()
    {
        if (defined('EBCMS_LINK_WEB_ROUTE')) {
            return;
        }
        Framework::get('/{key:[0-9a-zA-Z]{8}}', Jump::class, [], [], '/ebcms/link/jump');
    }

    public static function onInstall()
    {
        $sql = self::getInstallSql();
        start_one:
        fwrite(STDOUT, "是否安装演示数据？ [yes|no]：");
        switch (trim((string) fgets(STDIN))) {
            case 'yes':
                fwrite(STDOUT, "安装演示数据\n");
                $sql .= PHP_EOL . self::getDemoSql();
                break;

            case 'no':
                fwrite(STDOUT, "不安装演示数据\n");
                break;

            default:
                goto start_one;
                break;
        }
        self::execSql($sql);
    }

    public static function onUninstall()
    {
        $sql = '';
        start_two:
        fwrite(STDOUT, "是否删除数据库？[yes|no]：");
        switch (trim((string) fgets(STDIN))) {
            case 'yes':
                fwrite(STDOUT, "删除数据库\n");
                $sql .= PHP_EOL . self::getUninstallSql();
                break;

            case 'no':
                fwrite(STDOUT, "保留数据库\n");
                break;

            default:
                goto start_two;
                break;
        }
        self::execSql($sql);
    }

    private static function execSql(string $sql)
    {
        $sqls = array_filter(explode(";" . PHP_EOL, $sql));

        $prefix = 'prefix_';
        $cfg_file = getcwd() . '/config/database.php';
        $cfg = (array)include $cfg_file;
        if (isset($cfg['master']['prefix'])) {
            $prefix = $cfg['master']['prefix'];
        }

        $dbh = new PDO("{$cfg['master']['database_type']}:host={$cfg['master']['server']};dbname={$cfg['master']['database_name']}", $cfg['master']['username'], $cfg['master']['password']);

        foreach ($sqls as $sql) {
            $dbh->exec(str_replace('prefix_', $prefix, $sql . ';'));
        }
    }

    private static function getInstallSql(): string
    {
        return <<<'str'
DROP TABLE IF EXISTS `prefix_ebcms_link_log`;
CREATE TABLE `prefix_ebcms_link_log` (
    `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `url_id` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '链接id',
    `url` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '跳转地址' COLLATE 'utf8mb4_general_ci',
    `time` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '时间',
    `remote_addr` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'remote_addr' COLLATE 'utf8mb4_general_ci',
    `http_user_agent` TEXT(65535) NOT NULL COMMENT 'http_user_agent' COLLATE 'utf8mb4_general_ci',
    `http_referer` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'http_referer' COLLATE 'utf8mb4_general_ci',
    `year` SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
    `month` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
    `day` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
    `state` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '等同于url_state',
    PRIMARY KEY (`id`) USING BTREE
) COMMENT = '跳转日志' COLLATE = 'utf8mb4_general_ci' ENGINE = InnoDB ROW_FORMAT = DYNAMIC AUTO_INCREMENT = 1;
DROP TABLE IF EXISTS `prefix_ebcms_link_url`;
CREATE TABLE `prefix_ebcms_link_url` (
    `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `key` char(8) NOT NULL DEFAULT '' COMMENT '链接key' COLLATE 'utf8mb4_general_ci',
    `url` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '跳转地址' COLLATE 'utf8mb4_general_ci',
    `tips` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '备注' COLLATE 'utf8mb4_general_ci',
    `state` TINYINT(3) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态 1启用 0停用',
    PRIMARY KEY (`id`) USING BTREE
) COMMENT = '链接地址' COLLATE = 'utf8mb4_general_ci' ENGINE = InnoDB ROW_FORMAT = DYNAMIC AUTO_INCREMENT = 1;
str;
    }

    private static function getDemoSql(): string
    {
        return <<<'str'
str;
    }

    private static function getUninstallSql(): string
    {
        return <<<'str'
DROP TABLE IF EXISTS `prefix_ebcms_link_log`;
DROP TABLE IF EXISTS `prefix_ebcms_link_url`;
str;
    }
}
