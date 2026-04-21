<?php

namespace App\Notifications;

use App\Models\Proposal;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProposalRejectedNotification extends Notification
{
    use Queueable;

    public function __construct(public Proposal $proposal) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $p = $this->proposal->loadMissing(['topic', 'selectedDosen', 'kaprodiRecommendedDosen']);

        $topicName = optional($p->topic)->name ?? '-';
        $selectedDosenName = optional($p->selectedDosen)->name ?? 'dosen yang dipilih';
        $recommendedDosenName = optional($p->kaprodiRecommendedDosen)->name;
        $rejectionReason = $p->rejection_reason ?? '-';

        // Detect apakah karena kuota penuh
        $isQuotaFull = str_contains(strtolower($rejectionReason), 'kuota');

        $mail = (new MailMessage)
            ->subject('Update Pengajuan Skripsi: DITOLAK ❌')
            ->greeting('Halo, ' . ($notifiable->name ?? 'Mahasiswa') . ' 👋')
            ->line('Mohon maaf, pengajuan dosen pembimbing kamu telah ditinjau dan dinyatakan **DITOLAK**.')
            ->line('**Detail Pengajuan:**')
            ->line('• Judul: "' . ($p->title ?? '-') . '"')
            ->line('• Bidang Minat: ' . $topicName)
            ->line('---');

        // 🔴 KASUS: KUOTA PENUH
        if ($isQuotaFull) {
            $mail->line('**⚠️ ALASAN PENOLAKAN:**')
                ->line('Kuota dosen pembimbing **' . $selectedDosenName . '** sudah penuh.');

            if ($recommendedDosenName) {
                $mail->line(
                    'Kami merekomendasikan memilih dosen pembimbing **' . $recommendedDosenName . '** pada bidang yang sama karena kuota masih tersedia.'
                )
                ->line('Silakan kembali ke dashboard untuk mengubah pilihan dosen pembimbing.');
            } else {
                $mail->line('Silakan memilih dosen pembimbing lain melalui dashboard.');
            }
        }

        // 🔵 KASUS: BUKAN KUOTA
        else {
            $mail->line('**⚠️ ALASAN PENOLAKAN:**')
                ->line($rejectionReason);
        }

        return $mail
            ->line('---')
            ->action('Lihat Detail di Dashboard', route('student.dashboard'))
            ->line('Jika ada pertanyaan lebih lanjut, silakan hubungi bagian akademik.')
            ->salutation('Salam hangat,' . "\r\n" . 'Kaprodi FASILKOM UNSIKA');
    }
}