<?php

/**
 * SiteConfig モデルのテスト
 *
 * baserCMS :  Based Website Development Project <http://basercms.net>
 * Copyright 2008 - 2015, baserCMS SiteConfigs Community <http://sites.google.com/site/baserSiteConfigs/>
 *
 * @copyright   Copyright 2008 - 2015, baserCMS SiteConfigs Community
 * @link      http://basercms.net baserCMS Project
 * @since     baserCMS v 3.0.0-beta
 * @license     http://basercms.net/license/index.html
 */
App::uses('SiteConfig', 'Model');

/**
 * SiteConfigTest class
 * 
 * class NonAssosiationSiteConfig extends SiteConfig {
 *  public $name = 'SiteConfig';
 *  public $belongsTo = array();
 *  public $hasMany = array();
 * }
 * 
 * @package Baser.Test.Case.Model
 */
class SiteConfigTest extends BaserTestCase {

  public $fixtures = array(
    'baser.Default.SiteConfig',
  );

  public function setUp() {
    parent::setUp();
    $this->SiteConfig = ClassRegistry::init('SiteConfig');
  }

  public function tearDown() {
    unset($this->SiteConfig);
    parent::tearDown();
  }

/**
 * validate
 */
  public function test必須チェック異常系() {
    $this->SiteConfig->create(array(
      'SiteConfig' => array(
          'email' => ''
        )
    ));
    $this->assertFalse($this->SiteConfig->validates());
    $this->assertArrayHasKey('formal_name', $this->SiteConfig->validationErrors);
    $this->assertEquals('Webサイト名を入力してください。', current($this->SiteConfig->validationErrors['formal_name']));
    $this->assertArrayHasKey('name', $this->SiteConfig->validationErrors);
    $this->assertEquals('Webサイトタイトルを入力してください。', current($this->SiteConfig->validationErrors['name']));
    $this->assertArrayHasKey('email', $this->SiteConfig->validationErrors);
    $this->assertEquals('管理者メールアドレスの形式が不正です。', current($this->SiteConfig->validationErrors['email']));
    $this->assertArrayHasKey('mail_encode', $this->SiteConfig->validationErrors);
    $this->assertEquals('メール送信文字コードを入力してください。初期値は「ISO-2022-JP」です。', current($this->SiteConfig->validationErrors['mail_encode']));
    $this->assertArrayHasKey('site_url', $this->SiteConfig->validationErrors);
    $this->assertEquals('WebサイトURLを入力してください。', current($this->SiteConfig->validationErrors['site_url']));
  }

  public function test必須チェック正常系() {
    $this->SiteConfig->create(array(
      'SiteConfig' => array(
          'formal_name' => 'hoge',
          'name' => 'hoge',
          'email' => 'hoge@ho.ge',
          'mail_encode' => 'ISO-2022-JP',
          'site_url' => 'hoge',
        )
    ));
    $this->assertTrue($this->SiteConfig->validates());
  }

  public function testSSLチェック異常系() {
    $this->SiteConfig->create(array(
      'SiteConfig' => array(
          'formal_name' => 'hoge',
          'name' => 'hoge',
          'email' => 'hoge@ho.ge',
          'mail_encode' => 'ISO-2022-JP',
          'site_url' => 'hoge',
          'admin_ssl' => 'hoge',
          'ssl_url' => '',
        )
    ));
    $this->assertFalse($this->SiteConfig->validates());
    $this->assertArrayHasKey('admin_ssl', $this->SiteConfig->validationErrors);
    $this->assertEquals('管理画面をSSLで利用するには、SSL用のWebサイトURLを入力してください。', current($this->SiteConfig->validationErrors['admin_ssl']));
  }

  public function testSSLチェック正常系() {
    $this->SiteConfig->create(array(
      'SiteConfig' => array(
          'formal_name' => 'hoge',
          'name' => 'hoge',
          'email' => 'hoge@ho.ge',
          'mail_encode' => 'ISO-2022-JP',
          'site_url' => 'hoge',
          'admin_ssl' => 'hoge',
          'ssl_url' => 'hoge'
        )
    ));
    $this->assertTrue($this->SiteConfig->validates());
  }

/**
 * テーマの一覧を取得する
 */
  public function testGetTheme() {
    $result = $this->SiteConfig->getThemes();
    $expected = array(
      'm-single' => 'M-single',
      'nada-icons' => 'Nada-icons',
      'skelton' => 'Skelton'
    );
    $this->assertEquals($expected, $result);
  }

/**
 * コントロールソースを取得する
 * 
 * @param string $field フィールド名
 * @param array $expected 期待値
 * @param string $message テストが失敗した時に表示されるメッセージ
 * @dataProvider getControlSourceDataProvider
 */
  public function testGetControlSource($field, $expected, $message = null) {
    $result = $this->SiteConfig->getControlSource($field);
    $this->assertEquals($expected, $result, $message);
  }

/**
 * getControlSource用データプロバイダ
 *
 * @return array
 */
  public function getControlSourceDataProvider() {
    return array(
      array('mode', array(
        -1 => 'インストールモード',
        0 => 'ノーマルモード',
        1 => 'デバッグモード１',
        2 => 'デバッグモード２',
        3 => 'デバッグモード３',
        ), 'コントロールソースを取得できません'),
      array('hoge', false, '存在しないキーです'),
    );
  }

}
