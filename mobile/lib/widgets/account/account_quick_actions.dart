import 'package:flutter/material.dart';

import '../../config/theme.dart';

class AccountQuickActions extends StatelessWidget {
  const AccountQuickActions({
    super.key,
    required this.pendingCount,
    required this.packedCount,
    required this.shippedCount,
    required this.onOrderHistory,
    required this.onEditProfile,
    required this.onAddresses,
    required this.onWishlist,
    required this.onSecurity,
    required this.onHelp,
    required this.onFaq,
    required this.onPromo,
    required this.onPanduanUkuran,
    required this.onKebijakanPengembalian,
    required this.onKebijakanPrivasi,
    required this.onSyaratKetentuan,
    required this.onTentangKami,
  });

  final int pendingCount;
  final int packedCount;
  final int shippedCount;
  final VoidCallback onOrderHistory;
  final VoidCallback onEditProfile;
  final VoidCallback onAddresses;
  final VoidCallback onWishlist;
  final VoidCallback onSecurity;
  final VoidCallback onHelp;
  final VoidCallback onFaq;
  final VoidCallback onPromo;
  final VoidCallback onPanduanUkuran;
  final VoidCallback onKebijakanPengembalian;
  final VoidCallback onKebijakanPrivasi;
  final VoidCallback onSyaratKetentuan;
  final VoidCallback onTentangKami;

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Card(
          elevation: 0,
          color: AppTheme.surfaceContainerLowest,
          shape: RoundedRectangleBorder(borderRadius: AppTheme.radius19),
          child: Padding(
            padding: const EdgeInsets.all(20.0),
            child: Column(
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    const Text(
                      'Pesanan Saya',
                      style: TextStyle(
                        fontWeight: FontWeight.bold,
                        fontSize: 16,
                      ),
                    ),
                    TextButton(
                      onPressed: onOrderHistory,
                      style: TextButton.styleFrom(
                        padding: EdgeInsets.zero,
                        minimumSize: Size.zero,
                        tapTargetSize: MaterialTapTargetSize.shrinkWrap,
                      ),
                      child: const Text(
                        'Lihat Riwayat',
                        style: TextStyle(
                          color: AppTheme.primary,
                          fontSize: 13,
                          fontWeight: FontWeight.w700,
                        ),
                      ),
                    ),
                  ],
                ),
                const Divider(height: 28),
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceAround,
                  children: [
                    _StatusItem(
                      Icons.payment_outlined,
                      'Belum Bayar',
                      count: pendingCount,
                    ),
                    _StatusItem(
                      Icons.inventory_2_outlined,
                      'Dikemas',
                      count: packedCount,
                    ),
                    _StatusItem(
                      Icons.local_shipping_outlined,
                      'Dikirim',
                      count: shippedCount,
                    ),
                    const _StatusItem(Icons.chat_bubble_outline, 'Bantuan'),
                  ],
                ),
              ],
            ),
          ),
        ),
        const SizedBox(height: 16),
        Container(
          decoration: BoxDecoration(
            color: AppTheme.surfaceContainerLowest,
            borderRadius: AppTheme.radius19,
          ),
          child: Column(
            children: [
              _SettingsTile(Icons.person_outline, 'Edit Profil', onEditProfile),
              _SettingsTile(
                Icons.location_on_outlined,
                'Daftar Alamat',
                onAddresses,
              ),
              _SettingsTile(Icons.favorite_border, 'Wishlist', onWishlist),
              _SettingsTile(
                Icons.security_outlined,
                'Keamanan Akun',
                onSecurity,
              ),
              _SettingsTile(Icons.info_outline, 'Tentang Kami', onTentangKami),
              _SettingsTile(Icons.help_outline, 'Pusat Bantuan', onHelp),
            ],
          ),
        ),
        const SizedBox(height: 16),
        const Padding(
          padding: EdgeInsets.only(left: 8, bottom: 8),
          child: Align(
            alignment: Alignment.centerLeft,
            child: Text(
              'Info & Kebijakan',
              style: TextStyle(
                fontWeight: FontWeight.bold,
                fontSize: 14,
                color: AppTheme.slate500,
              ),
            ),
          ),
        ),
        Container(
          decoration: BoxDecoration(
            color: AppTheme.surfaceContainerLowest,
            borderRadius: AppTheme.radius19,
          ),
          child: Column(
            children: [
              _SettingsTile(Icons.help_outline, 'FAQ', onFaq),
              _SettingsTile(Icons.local_offer_outlined, 'Promo', onPromo),
              _SettingsTile(
                Icons.straighten_outlined,
                'Panduan Ukuran',
                onPanduanUkuran,
              ),
              _SettingsTile(
                Icons.sync_outlined,
                'Kebijakan Pengembalian',
                onKebijakanPengembalian,
              ),
              _SettingsTile(
                Icons.privacy_tip_outlined,
                'Kebijakan Privasi',
                onKebijakanPrivasi,
              ),
              _SettingsTile(
                Icons.description_outlined,
                'Syarat Ketentuan',
                onSyaratKetentuan,
              ),
            ],
          ),
        ),
      ],
    );
  }
}

class _StatusItem extends StatelessWidget {
  const _StatusItem(this.icon, this.label, {this.count = 0});

  final IconData icon;
  final String label;
  final int count;

  @override
  Widget build(BuildContext context) {
    return Stack(
      clipBehavior: Clip.none,
      children: [
        Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Icon(icon, color: AppTheme.slate500, size: 28),
            const SizedBox(height: 8),
            Text(
              label,
              textAlign: TextAlign.center,
              style: const TextStyle(
                fontSize: 12,
                color: AppTheme.slate500,
                fontWeight: FontWeight.w600,
              ),
            ),
          ],
        ),
        if (count > 0)
          Positioned(
            right: -6,
            top: -6,
            child: Container(
              padding: const EdgeInsets.all(4),
              decoration: const BoxDecoration(
                color: AppTheme.error,
                shape: BoxShape.circle,
              ),
              child: Text(
                count > 9 ? '9+' : count.toString(),
                style: const TextStyle(
                  color: Colors.white,
                  fontSize: 10,
                  fontWeight: FontWeight.bold,
                ),
              ),
            ),
          ),
      ],
    );
  }
}

class _SettingsTile extends StatelessWidget {
  const _SettingsTile(this.icon, this.label, this.onTap);

  final IconData icon;
  final String label;
  final VoidCallback onTap;

  @override
  Widget build(BuildContext context) {
    return ListTile(
      leading: Container(
        width: 36,
        height: 36,
        decoration: BoxDecoration(
          color: AppTheme.slate50,
          borderRadius: BorderRadius.circular(12),
        ),
        child: Icon(icon, color: AppTheme.slate500, size: 18),
      ),
      title: Text(
        label,
        style: const TextStyle(
          fontSize: 14,
          fontWeight: FontWeight.w600,
          color: AppTheme.slate800,
        ),
      ),
      trailing: const Icon(Icons.chevron_right, color: AppTheme.slate200),
      onTap: onTap,
    );
  }
}
