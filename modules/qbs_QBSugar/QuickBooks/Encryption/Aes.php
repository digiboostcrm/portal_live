<?php

/** 
 * AES Encryption (depends on mcrypt for now)
 * 
 * Copyright (c) 2010 Keith Palmer / ConsoliBYTE, LLC.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.opensource.org/licenses/eclipse-1.0.php
 * 
 * @author Keith Palmer <keith@ConsoliBYTE.com>
 * 
 * @package QuickBooks
 */

// 
QuickBooks_Loader::load('/QuickBooks/Encryption.php');

/**
 * 
 */
class QuickBooks_Encryption_AES extends QuickBooks_Encryption
{
	public static $cipher = 'AES-128-CBC';

	static function encrypt($key, $plain, $salt = null)
	{
		if (is_null($salt))
		{
			$salt = QuickBooks_Encryption::salt();
		}

		$plain = serialize(array( $plain, $salt ));

		$cipher = QuickBooks_Encryption_AES::$cipher;
		$ivlen = openssl_cipher_iv_length($cipher);
		$cipher_text = openssl_encrypt($plain, $cipher, $key, $options=0);
		return $cipher_text;
	}

	static function decrypt($key, $encrypted)
	{
		$cipher = QuickBooks_Encryption_AES::$cipher;
		$ivlen = openssl_cipher_iv_length($cipher);

		$plaintext = openssl_decrypt($encrypted, $cipher, $key, $options=0);
		$unserialized_plaintext = unserialize($plaintext);
		$decrypted_plaintext = current($unserialized_plaintext);
		return $decrypted_plaintext;
	}
}
