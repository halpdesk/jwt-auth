<?php

/*
 * This file is part of jwt-auth.
 *
 * (c) Sean Tymon <tymon148@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tymon\JWTAuth\Test\Validators;

use Tymon\JWTAuth\Test\AbstractTestCase;
use Tymon\JWTAuth\Validators\TokenValidator;

class TokenValidatorTest extends AbstractTestCase
{
    /**
     * @var \Tymon\JWTAuth\Validators\TokenValidator
     */
    protected $validator;

    public function setUp() : void
    {
        parent::setUp();

        $this->validator = new TokenValidator;
    }

    /** @test */
    public function it_should_return_true_when_providing_a_well_formed_token()
    {
        $this->assertTrue($this->validator->isValid('one.two.three'));
    }

    public function dataProviderMalformedTokens()
    {
        return [
            ['one.two.'],
            ['.two.'],
            ['.two.three'],
            ['one..three'],
            ['..'],
            [' . . '],
            [' one . two . three '],
        ];
    }

    /**
     * @test
     * @dataProvider \Tymon\JWTAuth\Test\Validators\TokenValidatorTest::dataProviderMalformedTokens
     *
     * @param  string  $token
     */
    public function it_should_return_false_when_providing_a_malformed_token($token)
    {
        $this->assertFalse($this->validator->isValid($token));
    }

    /**
     * @test
     * @dataProvider \Tymon\JWTAuth\Test\Validators\TokenValidatorTest::dataProviderMalformedTokens
     *
     * @param  string  $token
     */
    public function it_should_throw_an_exception_when_providing_a_malformed_token($token)
    {
        $thrown = false;
        try {
            $this->validator->check($token);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            $thrown = true;
        }
    }

    public function dataProviderTokensWithWrongSegmentsNumber()
    {
        return [
            ['one.two'],
            ['one.two.three.four'],
            ['one.two.three.four.five'],
        ];
    }

    /**
     * @test
     * @dataProvider \Tymon\JWTAuth\Test\Validators\TokenValidatorTest::dataProviderTokensWithWrongSegmentsNumber
     *
     * @param  string  $token
     */
    public function it_should_return_false_when_providing_a_token_with_wrong_segments_number($token)
    {
        $this->assertFalse($this->validator->isValid($token));
    }

    /**
     * @test
     * @dataProvider \Tymon\JWTAuth\Test\Validators\TokenValidatorTest::dataProviderTokensWithWrongSegmentsNumber
     *
     * @param  string  $token
     */
    public function it_should_throw_an_exception_when_providing_a_malformed_token_with_wrong_segments_number($token)
    {
        $thrown = false;
        try {
            $this->validator->check($token);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            $thrown = true;
        }
    }
}
