<style>
  .footer{
    margin-top:56px;
    border-top:1px solid #ededed;
    background:#fff;
  }
  .footer-inner{
    width:100%;
    max-width:none;
    margin:0;
    padding:20px 32px;
  }
  .footer-layout{
    width:100%;
    display:grid;
    grid-template-columns:minmax(0,1fr) auto minmax(0,1fr);
    align-items:center;
    column-gap:24px;
  }
  .footer-block{
    display:flex;
    align-items:center;
    gap:14px;
    min-height:34px;
    min-width:0;
    white-space:nowrap;
  }
  .footer-block + .footer-block{
    border-left:1px solid #d7dde7;
    padding-left:24px;
  }
  .footer-block a,
  .footer-block a:visited{
    color:#616b7d !important;
    text-decoration:none !important;
    font-size:13px;
    font-weight:500;
  }
  .footer-block a:hover{
    color:#86c440 !important;
    text-decoration:none !important;
  }
  .footer-block span{
    color:#8b93a3;
    font-size:13px;
  }
  .footer-copy{
    font-size:11px;
    color:#7f8796;
    letter-spacing:.25px;
    white-space:nowrap;
  }
  .footer-block-1{
    justify-content:flex-start;
    overflow:visible;
  }
  .footer-block-2{
    justify-self:center;
    justify-content:center;
  }
  .footer-block-3{
    justify-self:end;
    justify-content:flex-end;
    gap:14px;
  }
  .footer-copy{
    margin-left:8px;
    text-align:right;
  }
  .footer-social{
    display:flex;
    align-items:center;
    gap:12px;
  }
  .footer-right i{
    color:#6a7282;
    font-size:16px;
    cursor:pointer;
  }
  .footer-right i:hover{
    color:#86c440;
    transform:scale(1.08);
  }
  @media (max-width: 1100px){
    .footer-layout{
      grid-template-columns:1fr;
      row-gap:8px;
    }
    .footer-block + .footer-block{
      border-left:0;
      padding-left:0;
    }
    .footer-block-1,
    .footer-block-2,
    .footer-block-3{ justify-content:flex-start; }
  }

  .footer-block-2{
    border-left:0 !important;
    padding-left:0 !important;
  }
  @media (max-width: 600px){
    .footer-inner{
      padding:14px 12px;
    }
    .footer-layout{
      row-gap:6px;
    }
    .footer-block{
      justify-content:center !important;
      gap:10px;
      min-height:28px;
      flex-wrap:wrap;
    }
    .footer-block a,
    .footer-block a:visited,
    .footer-block span{
      font-size:12px;
    }
    .footer-block-3{
      width:100%;
      justify-content:center !important;
      gap:10px;
      flex-wrap:nowrap;
    }
    .footer-social{
      gap:10px;
    }
    .footer-right i{
      font-size:15px;
    }
    .footer-copy{
      margin-left:0;
      font-size:10px;
      text-align:center;
    }
  }
</style>
<footer class="footer">
  <div class="footer-inner">
    <div class="footer-layout">
      <div class="footer-block footer-block-1">
        <a href="Homepage.php">Inicio</a>
        <span>·</span>
        <a href="Homepage.php#contacto">Contacto</a>
        <span>·</span>
        <a href="MiCuenta.php">Mi Cuenta</a>
      </div>
      <div class="footer-block footer-block-2">
        <a href="Perros.php">Perros</a>
        <span>·</span>
        <a href="Gatos.php">Gatos</a>
        <span>·</span>
        <a href="Pajaros.php">Pájaros</a>
        <span>·</span>
        <a href="Peces.php">Peces</a>
      </div>
      <div class="footer-block footer-block-3 footer-right">
        <div class="footer-social">
          <a href="https://www.facebook.com/share/1AsKfSyiEw/?mibextid=wwXIfr" target="_blank" rel="noopener noreferrer" aria-label="Facebook" title="Facebook">
            <i class="fa-brands fa-facebook-f"></i>
          </a>
          <a href="https://www.instagram.com/hadri_romero?igsh=MW92cW1pNGMybHB1Mw==" target="_blank" rel="noopener noreferrer" aria-label="Instagram" title="Instagram">
            <i class="fa-brands fa-instagram"></i>
          </a>
          <a href="https://x.com/hadri_romeroo?s=21" target="_blank" rel="noopener noreferrer" aria-label="X" title="X">
            <i class="fa-brands fa-x-twitter"></i>
          </a>
        </div>
        <span class="footer-copy">© 2026 WildPet</span>
      </div>
    </div>
  </div>
</footer>
