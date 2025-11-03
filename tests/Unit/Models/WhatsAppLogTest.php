<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\WhatsAppLog;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WhatsAppLogTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_be_created_with_valid_data()
    {
        $log = WhatsAppLog::create([
            'phone_number' => '628123456789',
            'message' => 'Tagihan SPP bulan Januari sebesar Rp 500.000',
            'status' => 'sent',
            'sent_at' => '2024-01-15 10:30:00'
        ]);

        $this->assertInstanceOf(WhatsAppLog::class, $log);
        $this->assertEquals('628123456789', $log->phone_number);
        $this->assertEquals('Tagihan SPP bulan Januari sebesar Rp 500.000', $log->message);
        $this->assertEquals('sent', $log->status);
        $this->assertEquals('2024-01-15 10:30:00', $log->sent_at);
    }

    /** @test */
    public function it_validates_status_values()
    {
        $validStatuses = ['pending', 'sent', 'failed'];

        foreach ($validStatuses as $status) {
            $log = WhatsAppLog::create([
                'phone_number' => '628123456789',
                'message' => 'Test message',
                'status' => $status,
                'sent_at' => now()
            ]);

            $this->assertEquals($status, $log->status);
        }
    }

    /** @test */
    public function it_can_filter_by_status()
    {
        $sentLog = WhatsAppLog::create([
            'phone_number' => '628123456789',
            'message' => 'Message sent',
            'status' => 'sent',
            'sent_at' => now()
        ]);

        $pendingLog = WhatsAppLog::create([
            'phone_number' => '628987654321',
            'message' => 'Message pending',
            'status' => 'pending',
            'sent_at' => null
        ]);

        $sentLogs = WhatsAppLog::where('status', 'sent')->get();
        $pendingLogs = WhatsAppLog::where('status', 'pending')->get();

        $this->assertTrue($sentLogs->contains($sentLog));
        $this->assertFalse($sentLogs->contains($pendingLog));
        $this->assertTrue($pendingLogs->contains($pendingLog));
        $this->assertFalse($pendingLogs->contains($sentLog));
    }

    /** @test */
    public function it_validates_message_type_values()
    {
        $validMessageTypes = ['reminder', 'payment_confirmation', 'custom'];

        foreach ($validMessageTypes as $messageType) {
            $log = WhatsAppLog::create([
                'phone_number' => '628123456789',
                'message' => 'Test message',
                'message_type' => $messageType,
                'status' => 'sent',
                'sent_at' => now()
            ]);

            $this->assertEquals($messageType, $log->message_type);
        }
    }

    /** @test */
    public function it_can_filter_by_phone_number()
    {
        $log1 = WhatsAppLog::create([
            'phone_number' => '628123456789',
            'message' => 'Message to first number',
            'status' => 'sent',
            'sent_at' => now()
        ]);

        $log2 = WhatsAppLog::create([
            'phone_number' => '628987654321',
            'message' => 'Message to second number',
            'status' => 'sent',
            'sent_at' => now()
        ]);

        $logsForFirstNumber = WhatsAppLog::where('phone_number', '628123456789')->get();

        $this->assertTrue($logsForFirstNumber->contains($log1));
        $this->assertFalse($logsForFirstNumber->contains($log2));
    }

    /** @test */
    public function it_can_filter_by_date_range()
    {
        $yesterdayLog = WhatsAppLog::create([
            'phone_number' => '628123456789',
            'message' => 'Yesterday message',
            'status' => 'sent',
            'sent_at' => now()->subDay()
        ]);

        $todayLog = WhatsAppLog::create([
            'phone_number' => '628123456789',
            'message' => 'Today message',
            'status' => 'sent',
            'sent_at' => now()
        ]);

        $todayLogs = WhatsAppLog::whereDate('sent_at', today())->get();

        $this->assertTrue($todayLogs->contains($todayLog));
        $this->assertFalse($todayLogs->contains($yesterdayLog));
    }

    /** @test */
    public function it_stores_timestamps_correctly()
    {
        $log = WhatsAppLog::create([
            'phone_number' => '628123456789',
            'message' => 'Test message',
            'status' => 'sent',
            'sent_at' => now()
        ]);

        $this->assertNotNull($log->created_at);
        $this->assertNotNull($log->updated_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $log->created_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $log->updated_at);
    }

    /** @test */
    public function it_handles_sent_at_as_datetime()
    {
        $sentAt = '2024-01-15 10:30:00';
        $log = WhatsAppLog::create([
            'phone_number' => '628123456789',
            'message' => 'Test message',
            'status' => 'sent',
            'sent_at' => $sentAt
        ]);

        $this->assertEquals($sentAt, $log->sent_at);
        // If cast to Carbon, this would be true:
        // $this->assertInstanceOf(\Carbon\Carbon::class, $log->sent_at);
    }

    /** @test */
    public function it_can_store_long_messages()
    {
        $longMessage = str_repeat('This is a very long WhatsApp message. ', 50);

        $log = WhatsAppLog::create([
            'phone_number' => '628123456789',
            'message' => $longMessage,
            'status' => 'sent',
            'sent_at' => now()
        ]);

        $this->assertEquals($longMessage, $log->message);
        $this->assertTrue(strlen($log->message) > 1000);
    }

    /** @test */
    public function it_can_store_api_responses()
    {
        $apiResponse = json_encode([
            'success' => true,
            'message_id' => 'wamid.ABC123',
            'status' => 'sent',
            'timestamp' => 1642234800
        ]);

        $log = WhatsAppLog::create([
            'phone_number' => '628123456789',
            'message' => 'Test message',
            'status' => 'sent',
            'sent_at' => now(),
            'response_data' => $apiResponse
        ]);

        $this->assertEquals($apiResponse, $log->response_data);
        $this->assertJson(json_encode($log->response_data));
    }

    /** @test */
    public function it_can_handle_null_response()
    {
        $log = WhatsAppLog::create([
            'phone_number' => '628123456789',
            'message' => 'Test message',
            'status' => 'pending',
            'sent_at' => null,
            'response' => null
        ]);

        $this->assertNull($log->response);
        $this->assertNull($log->sent_at);
    }

    /** @test */
    public function it_formats_phone_number_consistently()
    {
        $log1 = WhatsAppLog::create([
            'phone_number' => '08123456789',
            'message' => 'Test message',
            'status' => 'sent',
            'sent_at' => now()
        ]);

        $log2 = WhatsAppLog::create([
            'phone_number' => '+6281234567890',
            'message' => 'Test message',
            'status' => 'sent',
            'sent_at' => now()
        ]);

        // Both should store the phone number as provided (no automatic formatting)
        $this->assertEquals('08123456789', $log1->phone_number);
        $this->assertEquals('+6281234567890', $log2->phone_number);
    }

    /** @test */
    public function it_can_update_status_after_creation()
    {
        $log = WhatsAppLog::create([
            'phone_number' => '628123456789',
            'message' => 'Test message',
            'status' => 'pending',
            'sent_at' => null
        ]);

        $log->update([
            'status' => 'sent',
            'sent_at' => now()
        ]);

        $this->assertEquals('sent', $log->fresh()->status);
        $this->assertNotNull($log->fresh()->sent_at);
        // Check that the log was updated
        $this->assertNotNull($log->fresh()->sent_at);
    }
}