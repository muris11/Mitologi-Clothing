import 'package:flutter/material.dart';

import '../../config/theme.dart';
import '../animations/animations.dart';

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
        // Orders Section
        FadeInUp(
          delay: const Duration(milliseconds: 100),
          child: _buildCard(
            child: Column(
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    const Text(
                      'Pesanan Saya',
                      style: TextStyle(
                        fontWeight: FontWeight.w900,
                        fontSize: 18,
                        letterSpacing: -0.5,
                      ),
                    ),
                    TextButton(
                      onPressed: onOrderHistory,
                      style: TextButton.styleFrom(
                        padding: const EdgeInsets.symmetric(horizontal: 12),
                        backgroundColor: AppTheme.primary.withValues(alpha: 0.05),
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(20),
                        ),
                      ),
                      child: const Text(
                        'Riwayat',
                        style: TextStyle(
                          color: AppTheme.primary,
                          fontSize: 12,
                          fontWeight: FontWeight.w800,
                        ),
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 20),
                Row(
                  children: [
                    Expanded(
                      child: _StatusItem(
                        Icons.payment_outlined,
                        'Pesan',
                        count: pendingCount,
                        onTap: onOrderHistory,
                        color: Colors.orange,
                      ),
                    ),
                    const SizedBox(width: 8),
                    Expanded(
                      child: _StatusItem(
                        Icons.inventory_2_outlined,
                        'Kemas',
                        count: packedCount,
                        onTap: onOrderHistory,
                        color: Colors.blue,
                      ),
                    ),
                    const SizedBox(width: 8),
                    Expanded(
                      child: _StatusItem(
                        Icons.local_shipping_outlined,
                        'Kirim',
                        count: shippedCount,
                        onTap: onOrderHistory,
                        color: Colors.green,
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ),
        ),
        const SizedBox(height: 24),
        
        // Settings Section
        FadeInUp(
          delay: const Duration(milliseconds: 200),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Padding(
                padding: EdgeInsets.only(left: 8, bottom: 12),
                child: Text(
                  'Pengaturan Akun',
                  style: TextStyle(
                    fontWeight: FontWeight.w800,
                    fontSize: 14,
                    color: AppTheme.onSurfaceVariant,
                    letterSpacing: 1,
                  ),
                ),
              ),
              _buildCard(
                padding: const EdgeInsets.symmetric(vertical: 8),
                child: Column(
                  children: [
                    _SettingsTile(Icons.person_outline, 'Edit Profil', onEditProfile),
                    _SettingsTile(Icons.location_on_outlined, 'Daftar Alamat', onAddresses),
                    _SettingsTile(Icons.favorite_border, 'Wishlist', onWishlist),
                    _SettingsTile(Icons.security_outlined, 'Keamanan', onSecurity),
                  ],
                ),
              ),
            ],
          ),
        ),
        const SizedBox(height: 24),

        // Support & Policy Section
        FadeInUp(
          delay: const Duration(milliseconds: 300),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Padding(
                padding: EdgeInsets.only(left: 8, bottom: 12),
                child: Text(
                  'Bantuan & Kebijakan',
                  style: TextStyle(
                    fontWeight: FontWeight.w800,
                    fontSize: 14,
                    color: AppTheme.onSurfaceVariant,
                    letterSpacing: 1,
                  ),
                ),
              ),
              _buildCard(
                padding: const EdgeInsets.symmetric(vertical: 8),
                child: Column(
                  children: [
                    _SettingsTile(Icons.help_outline, 'Pusat Bantuan', onHelp),
                    _SettingsTile(Icons.quiz_outlined, 'FAQ', onFaq),
                    _SettingsTile(Icons.straighten_outlined, 'Panduan Ukuran', onPanduanUkuran),
                    _SettingsTile(Icons.info_outline, 'Tentang Kami', onTentangKami),
                    _SettingsTile(Icons.description_outlined, 'Syarat & Ketentuan', onSyaratKetentuan),
                  ],
                ),
              ),
            ],
          ),
        ),
        const SizedBox(height: 32),
      ],
    );
  }

  Widget _buildCard({required Widget child, EdgeInsetsGeometry? padding}) {
    return Container(
      decoration: BoxDecoration(
        color: AppTheme.sectionBackground,
        borderRadius: AppTheme.radius24,
        border: Border.all(color: AppTheme.outlineLight.withValues(alpha: 0.5), width: 1.5),
        boxShadow: AppTheme.shadowSoft,
      ),
      padding: padding ?? const EdgeInsets.all(20),
      child: child,
    );
  }
}

class _StatusItem extends StatelessWidget {
  const _StatusItem(this.icon, this.label, {
    this.count = 0, 
    required this.onTap,
    this.color = AppTheme.primary,
  });

  final IconData icon;
  final String label;
  final int count;
  final VoidCallback onTap;
  final Color color;

  @override
  Widget build(BuildContext context) {
    return Material(
      color: Colors.transparent,
      child: InkWell(
        onTap: onTap,
        borderRadius: AppTheme.radius16,
        child: Container(
          padding: const EdgeInsets.symmetric(vertical: 16),
          decoration: BoxDecoration(
            color: color.withValues(alpha: 0.05),
            borderRadius: AppTheme.radius16,
            border: Border.all(color: color.withValues(alpha: 0.1), width: 1),
          ),
          child: Stack(
            clipBehavior: Clip.none,
            alignment: Alignment.center,
            children: [
              Column(
                mainAxisSize: MainAxisSize.min,
                children: [
                  Icon(icon, color: color, size: 24),
                  const SizedBox(height: 8),
                  Text(
                    label,
                    style: TextStyle(
                      fontSize: 11,
                      color: AppTheme.onSurface.withValues(alpha: 0.8),
                      fontWeight: FontWeight.w700,
                    ),
                  ),
                ],
              ),
              if (count > 0)
                Positioned(
                  top: -8,
                  right: 8,
                  child: Container(
                    padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                    decoration: BoxDecoration(
                      color: color,
                      borderRadius: BorderRadius.circular(10),
                      boxShadow: [
                        BoxShadow(
                          color: color.withValues(alpha: 0.3),
                          blurRadius: 4,
                          offset: const Offset(0, 2),
                        ),
                      ],
                    ),
                    child: Text(
                      count > 9 ? '9+' : count.toString(),
                      style: const TextStyle(
                        color: Colors.white,
                        fontSize: 9,
                        fontWeight: FontWeight.w900,
                      ),
                    ),
                  ),
                ),
            ],
          ),
        ),
      ),
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
      contentPadding: const EdgeInsets.symmetric(horizontal: 20, vertical: 2),
      leading: Container(
        padding: const EdgeInsets.all(8),
        decoration: BoxDecoration(
          color: AppTheme.surfaceContainerLow,
          borderRadius: AppTheme.radius12,
        ),
        child: Icon(icon, color: AppTheme.onSurfaceVariant, size: 20),
      ),
      title: Text(
        label,
        style: const TextStyle(
          fontSize: 14,
          fontWeight: FontWeight.w600,
          color: AppTheme.onSurface,
        ),
      ),
      trailing: const Icon(Icons.chevron_right_rounded, color: AppTheme.outline, size: 20),
      onTap: onTap,
    );
  }
}
